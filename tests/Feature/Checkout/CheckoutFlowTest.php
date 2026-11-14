<?php

namespace Tests\Feature\Checkout;

use App\Modules\Catalog\Models\AttributeSet;
use App\Modules\Catalog\Models\Product;
use App\Modules\Customers\Models\Customer;
use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Shipping\Models\ShippingZone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    protected Customer $customer;

    protected $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $set = AttributeSet::query()->create(['code' => 'honey', 'name' => 'Honey']);
        $product = Product::query()->create([
            'attribute_set_id' => $set->id, 'name' => ['bn' => 'মধু'],
            'slug' => 'test-honey', 'type' => 'simple', 'status' => 'active',
        ]);
        $this->variant = $product->variants()->create(['sku' => 'TST-1', 'price' => 50000, 'is_default' => true]);
        InventoryItem::query()->create(['variant_id' => $this->variant->id, 'on_hand' => 5, 'reserved' => 0]);

        $zone = ShippingZone::query()->create(['name' => 'Dhaka', 'match' => ['keyword' => 'dhaka'], 'priority' => 10]);
        $zone->rates()->create(['fee' => 6000, 'est_days_min' => 1, 'est_days_max' => 2]);

        $this->customer = Customer::query()->create(['phone' => '+8801812345678', 'phone_verified_at' => now()]);
    }

    protected function addToCart(int $qty = 1): void
    {
        $this->actingAs($this->customer, 'sanctum')
            ->postJson('/api/v1/store/cart/items', ['variant_id' => $this->variant->id, 'qty' => $qty])
            ->assertOk();
    }

    public function test_happy_path_places_order_and_reserves_stock(): void
    {
        $this->addToCart(2);

        $quote = $this->actingAs($this->customer, 'sanctum')
            ->postJson('/api/v1/store/checkout/quote', ['district' => 'Dhaka'])
            ->assertOk()->json('data');

        $this->assertSame(106000, $quote['grand_total']); // 2*50000 + 6000 delivery

        $response = $this->actingAs($this->customer, 'sanctum')
            ->postJson('/api/v1/store/checkout/place', [
                'district' => 'Dhaka', 'address_line' => 'House 1', 'name' => 'Test', 'phone' => '01812345678',
                'payment_method' => 'cod', 'expected_total' => $quote['grand_total'],
            ], ['Idempotency-Key' => Str::uuid()->toString()]);

        $response->assertCreated()->assertJsonPath('data.status', 'pending');

        $this->variant->inventoryItem->refresh();
        $this->assertSame(2, $this->variant->inventoryItem->reserved);
        $this->assertSame(5, $this->variant->inventoryItem->on_hand); // not decremented until shipped
    }

    public function test_insufficient_stock_is_rejected(): void
    {
        // CRT-7: adding to cart never reserves, so stock can still drop
        // between add and place (e.g. someone else bought it) — that gap
        // is exactly what checkout's own row-locked check must catch.
        $this->addToCart(3);
        $this->variant->inventoryItem->update(['on_hand' => 2]);

        $quote = $this->actingAs($this->customer, 'sanctum')
            ->postJson('/api/v1/store/checkout/quote', ['district' => 'Dhaka'])
            ->json('data');

        $this->actingAs($this->customer, 'sanctum')
            ->postJson('/api/v1/store/checkout/place', [
                'district' => 'Dhaka', 'address_line' => 'House 1', 'name' => 'Test', 'phone' => '01812345678',
                'payment_method' => 'cod', 'expected_total' => $quote['grand_total'],
            ], ['Idempotency-Key' => Str::uuid()->toString()])
            ->assertStatus(409)
            ->assertJsonPath('error.code', 'STOCK_INSUFFICIENT');
    }

    public function test_stale_expected_total_is_rejected(): void
    {
        $this->addToCart(1);

        $this->actingAs($this->customer, 'sanctum')
            ->postJson('/api/v1/store/checkout/place', [
                'district' => 'Dhaka', 'address_line' => 'House 1', 'name' => 'Test', 'phone' => '01812345678',
                'payment_method' => 'cod', 'expected_total' => 1, // wrong on purpose
            ], ['Idempotency-Key' => Str::uuid()->toString()])
            ->assertStatus(409)
            ->assertJsonPath('error.code', 'PRICE_CHANGED');
    }

    public function test_repeated_idempotency_key_returns_the_same_order(): void
    {
        $this->addToCart(1);
        $quote = $this->actingAs($this->customer, 'sanctum')
            ->postJson('/api/v1/store/checkout/quote', ['district' => 'Dhaka'])->json('data');
        $key = Str::uuid()->toString();

        $payload = [
            'district' => 'Dhaka', 'address_line' => 'House 1', 'name' => 'Test', 'phone' => '01812345678',
            'payment_method' => 'cod', 'expected_total' => $quote['grand_total'],
        ];

        $first = $this->actingAs($this->customer, 'sanctum')
            ->postJson('/api/v1/store/checkout/place', $payload, ['Idempotency-Key' => $key])
            ->assertCreated()->json('data.order_no');

        $second = $this->actingAs($this->customer, 'sanctum')
            ->postJson('/api/v1/store/checkout/place', $payload, ['Idempotency-Key' => $key])
            ->assertCreated()->json('data.order_no');

        $this->assertSame($first, $second);
        $this->assertSame(1, \App\Modules\Orders\Models\Order::query()->count());
    }
}
