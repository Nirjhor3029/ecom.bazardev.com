<?php

namespace App\Modules\Inventory\Services;

use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Inventory\Models\StockMovement;
use App\Modules\Inventory\Models\StockReservation;
use App\Exceptions\ApiException;
use App\Modules\Orders\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * ARC-4: every stock change goes through here. `reserved`/`on_hand` are
 * only ever touched inside these methods, each wrapped in the row lock
 * the caller already holds (CHK-R3 takes `lockForUpdate()` before calling in).
 */
class InventoryService
{
    /** Checkout time (CHK-R3b/c): lock, verify availability, reserve. Caller must already be inside a transaction. */
    public function reserveForVariant(int $variantId, int $qty, ?int $orderId = null, ?int $cartId = null): StockReservation
    {
        $item = InventoryItem::query()->where('variant_id', $variantId)->lockForUpdate()->first();

        if (! $item) {
            throw ValidationException::withMessages(['variant_id' => ['This item is not sellable.']]);
        }

        if ($item->track_stock && $item->available() < $qty && $item->backorder_policy === 'deny') {
            throw new ApiException(
                'STOCK_INSUFFICIENT',
                "Insufficient stock. Available: {$item->available()}.",
                409,
                ['variant_id' => $variantId, 'available' => $item->available()],
            );
        }

        $item->increment('reserved', $qty);

        return StockReservation::query()->create([
            'inventory_item_id' => $item->id,
            'order_id' => $orderId,
            'cart_id' => $cartId,
            'qty' => $qty,
            'status' => 'active',
            'expires_at' => $orderId ? null : now()->addMinutes(15),
        ]);
    }

    /** Order cancelled before shipping (ORD state table): release reservation, no stock_movement (on_hand unchanged). */
    public function releaseForOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $reservations = StockReservation::query()
                ->where('order_id', $order->id)->where('status', 'active')
                ->lockForUpdate()->get();

            foreach ($reservations as $reservation) {
                InventoryItem::query()->where('id', $reservation->inventory_item_id)
                    ->decrement('reserved', $reservation->qty);

                $reservation->update(['status' => 'released']);
            }
        });
    }

    /** packed -> shipped (ORD state table): on_hand--, reserved--, movement `ship`. */
    public function commitShipmentForOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $reservations = StockReservation::query()
                ->where('order_id', $order->id)->where('status', 'active')
                ->lockForUpdate()->get();

            foreach ($reservations as $reservation) {
                $item = InventoryItem::query()->lockForUpdate()->find($reservation->inventory_item_id);
                $item->decrement('on_hand', $reservation->qty);
                $item->decrement('reserved', $reservation->qty);

                StockMovement::query()->create([
                    'inventory_item_id' => $item->id,
                    'type' => 'ship',
                    'qty' => -$reservation->qty,
                    'reference_type' => Order::class,
                    'reference_id' => $order->id,
                ]);

                $reservation->update(['status' => 'committed']);
            }
        });
    }

    /** delivered -> returned & restocked: on_hand++, movement `return_restore`. */
    public function restoreFromReturn(int $variantId, int $qty, int $orderId): void
    {
        DB::transaction(function () use ($variantId, $qty, $orderId) {
            $item = InventoryItem::query()->where('variant_id', $variantId)->lockForUpdate()->first();

            if (! $item) {
                return;
            }

            $item->increment('on_hand', $qty);

            StockMovement::query()->create([
                'inventory_item_id' => $item->id,
                'type' => 'return_restore',
                'qty' => $qty,
                'reference_type' => Order::class,
                'reference_id' => $orderId,
            ]);
        });
    }

    /** Admin manual stock adjustment (INV-4). */
    public function adjust(int $variantId, int $delta, string $reason, ?int $actorId = null): InventoryItem
    {
        return DB::transaction(function () use ($variantId, $delta, $reason, $actorId) {
            $item = InventoryItem::query()->where('variant_id', $variantId)->lockForUpdate()->first();

            if (! $item) {
                throw ValidationException::withMessages(['variant_id' => ['Unknown variant.']]);
            }

            $item->increment('on_hand', $delta);

            StockMovement::query()->create([
                'inventory_item_id' => $item->id,
                'type' => 'adjust',
                'qty' => $delta,
                'reason' => $reason,
                'actor_id' => $actorId,
            ]);

            return $item->refresh();
        });
    }
}
