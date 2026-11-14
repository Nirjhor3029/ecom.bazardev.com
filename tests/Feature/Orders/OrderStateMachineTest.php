<?php

namespace Tests\Feature\Orders;

use App\Modules\Catalog\Models\AttributeSet;
use App\Modules\Catalog\Models\Product;
use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\OrderItem;
use App\Modules\Orders\Services\OrderStateMachine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStateMachineTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_transition_is_rejected(): void
    {
        $order = Order::query()->create($this->orderAttrs('pending'));

        try {
            app(OrderStateMachine::class)->transition($order, 'shipped'); // pending can't jump straight to shipped
            $this->fail('Expected ApiException was not thrown.');
        } catch (\App\Exceptions\ApiException $e) {
            $this->assertSame('INVALID_TRANSITION', $e->errorCode);
            $this->assertSame(409, $e->status);
        }
    }

    public function test_shipping_decrements_on_hand_and_delivering_marks_cod_paid(): void
    {
        $set = AttributeSet::query()->create(['code' => 'honey', 'name' => 'Honey']);
        $product = Product::query()->create([
            'attribute_set_id' => $set->id, 'name' => ['bn' => 'মধু'], 'slug' => 'x', 'type' => 'simple', 'status' => 'active',
        ]);
        $variant = $product->variants()->create(['sku' => 'TST-1', 'price' => 50000, 'is_default' => true]);
        $item = InventoryItem::query()->create(['variant_id' => $variant->id, 'on_hand' => 10, 'reserved' => 3]);

        $order = Order::query()->create($this->orderAttrs('packed', paymentMethod: 'cod'));
        OrderItem::query()->create([
            'order_id' => $order->id, 'variant_id' => $variant->id, 'name_snapshot' => ['bn' => 'x'],
            'sku' => 'TST-1', 'unit_price' => 50000, 'qty' => 3, 'line_total' => 150000,
        ]);
        \App\Modules\Inventory\Models\StockReservation::query()->create([
            'inventory_item_id' => $item->id, 'order_id' => $order->id, 'qty' => 3, 'status' => 'active',
        ]);

        $machine = app(OrderStateMachine::class);
        $machine->transition($order, 'shipped');

        $item->refresh();
        $this->assertSame(7, $item->on_hand);
        $this->assertSame(0, $item->reserved);

        $order = $machine->transition($order, 'delivered');
        $this->assertSame('delivered', $order->status);
        $this->assertSame('paid', $order->payment_status);
        $this->assertSame($order->grand_total, $order->paid_total);
    }

    protected function orderAttrs(string $status, string $paymentMethod = 'cod'): array
    {
        return [
            'order_no' => 'ORD-TEST-'.uniqid(),
            'status' => $status,
            'payment_status' => 'unpaid',
            'fulfillment_status' => 'unfulfilled',
            'subtotal' => 150000, 'grand_total' => 150000, 'due_total' => 150000,
            'currency' => 'BDT', 'payment_method' => $paymentMethod,
            'shipping_address' => ['name' => 'x', 'phone' => '01812345678'],
            'idempotency_key' => \Illuminate\Support\Str::uuid()->toString(),
        ];
    }
}
