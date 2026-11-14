<?php

namespace App\Modules\Checkout\Actions;

use App\Exceptions\PriceChangedException;
use App\Modules\Cart\Models\Cart;
use App\Modules\Checkout\Services\PricingService;
use App\Modules\Customers\Models\Customer;
use App\Modules\Inventory\Services\InventoryService;
use App\Modules\Notifications\Services\NotificationService;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\OrderStatusHistory;
use App\Modules\Orders\Support\OrderNumberGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * SRS CHK-R3: the one atomic place-order path. Lock + verify + reserve +
 * write order/items/history + commit, all or nothing. CHK-R4: a repeated
 * Idempotency-Key returns the original order instead of creating a new one.
 */
class PlaceOrderAction
{
    public function __construct(
        protected PricingService $pricing,
        protected InventoryService $inventory,
        protected NotificationService $notifications,
    ) {}

    public function execute(Cart $cart, Customer $customer, array $input, string $idempotencyKey): Order
    {
        $existing = Order::query()->where('idempotency_key', $idempotencyKey)->first();

        if ($existing) {
            return $existing->load('items');
        }

        if ($cart->items->isEmpty()) {
            throw ValidationException::withMessages(['cart' => ['Your cart is empty.']]);
        }

        return DB::transaction(function () use ($cart, $customer, $input, $idempotencyKey) {
            $priced = $this->pricing->calculate($cart, $input['district']);

            if ((int) $input['expected_total'] !== $priced['grand_total']) {
                throw new PriceChangedException($priced['grand_total']);
            }

            $order = Order::query()->create([
                'order_no' => OrderNumberGenerator::generate(),
                'customer_id' => $customer->id,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'fulfillment_status' => 'unfulfilled',
                'subtotal' => $priced['subtotal'],
                'discount_total' => $priced['discount_total'],
                'delivery_fee' => $priced['delivery_fee'],
                'tax_total' => $priced['tax_total'],
                'grand_total' => $priced['grand_total'],
                'paid_total' => 0,
                'due_total' => $priced['grand_total'],
                'currency' => 'BDT',
                'payment_method' => $input['payment_method'],
                'coupon_code' => $cart->coupon_code,
                'shipping_address' => [
                    'name' => $input['name'],
                    'phone' => $input['phone'],
                    'email' => $input['email'] ?? null,
                    'division' => $input['division'] ?? null,
                    'district' => $input['district'],
                    'upazila' => $input['upazila'] ?? null,
                    'area' => $input['area'] ?? null,
                    'address_line' => $input['address_line'],
                    'postcode' => $input['postcode'] ?? null,
                    'alt_name' => $input['alt_name'] ?? null,
                    'alt_phone' => $input['alt_phone'] ?? null,
                ],
                'note_customer' => $input['note'] ?? null,
                'source' => 'web',
                'placed_at' => now(),
                'idempotency_key' => $idempotencyKey,
            ]);

            foreach ($priced['lines'] as $line) {
                $variant = $cart->items->firstWhere('variant_id', $line['variant_id'])->variant;

                $order->items()->create([
                    'product_id' => $variant->product_id,
                    'variant_id' => $variant->id,
                    'name_snapshot' => $variant->product->name,
                    'sku' => $variant->sku,
                    'options' => $variant->options,
                    'unit_price' => $line['unit_price'],
                    'qty' => $line['qty'],
                    'line_total' => $line['line_total'],
                    'weight_grams' => $variant->weight_grams,
                ]);

                // Row-locked availability check + reservation (CHK-R3a/b/c) —
                // this is what actually guarantees NFR-2 (zero oversell),
                // not the earlier cart-add check which never reserves (CRT-7).
                $this->inventory->reserveForVariant($variant->id, $line['qty'], $order->id);
            }

            OrderStatusHistory::query()->create([
                'order_id' => $order->id, 'from' => null, 'to' => 'pending', 'actor_type' => 'system',
            ]);

            $cart->items()->delete();
            $cart->delete();

            $this->notifications->sendSms($input['phone'], 'order.placed', ['order_no' => $order->order_no]);

            return $order->load('items');
        });
    }
}
