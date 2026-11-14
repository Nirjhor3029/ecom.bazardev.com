<?php

namespace App\Modules\Orders\Services;

use App\Exceptions\ApiException;
use App\Modules\Inventory\Services\InventoryService;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\OrderStatusHistory;
use Illuminate\Support\Facades\DB;

/**
 * ARC-3: the only place order.status may change. SRS 5.9 transition table.
 */
class OrderStateMachine
{
    protected const array TRANSITIONS = [
        'pending' => ['confirmed', 'on_hold', 'cancelled', 'failed'],
        'confirmed' => ['processing', 'on_hold', 'cancelled', 'failed'],
        'on_hold' => ['confirmed', 'cancelled'],
        'processing' => ['packed', 'cancelled', 'failed'],
        'packed' => ['shipped', 'cancelled', 'failed'],
        'shipped' => ['delivered', 'returned'],
        'delivered' => ['returned'],
    ];

    public function __construct(protected InventoryService $inventory) {}

    public function transition(Order $order, string $to, ?string $note = null, string $actorType = 'staff', ?int $actorId = null): Order
    {
        $allowed = self::TRANSITIONS[$order->status] ?? [];

        if (! in_array($to, $allowed, true)) {
            throw new ApiException('INVALID_TRANSITION', "Cannot move order from [{$order->status}] to [{$to}].", 409);
        }

        return DB::transaction(function () use ($order, $to, $note, $actorType, $actorId) {
            $from = $order->status;

            $this->applySideEffects($order, $from, $to);

            $order->status = $to;

            if ($to === 'delivered') {
                $order->fulfillment_status = 'fulfilled';

                if ($order->payment_method === 'cod') {
                    $order->payment_status = 'paid';
                    $order->paid_total = $order->grand_total;
                    $order->due_total = 0;
                }
            }

            $order->save();

            OrderStatusHistory::query()->create([
                'order_id' => $order->id,
                'from' => $from,
                'to' => $to,
                'actor_type' => $actorType,
                'actor_id' => $actorId,
                'note' => $note,
            ]);

            return $order;
        });
    }

    protected function applySideEffects(Order $order, string $from, string $to): void
    {
        if ($to === 'cancelled' && in_array($from, ['pending', 'confirmed', 'processing', 'on_hold'], true)) {
            $this->inventory->releaseForOrder($order);
        }

        if ($to === 'shipped') {
            $this->inventory->commitShipmentForOrder($order);
        }
    }
}
