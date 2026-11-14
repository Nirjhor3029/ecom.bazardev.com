<?php

namespace Tests\Feature\Storefront;

use App\Modules\Cart\Models\Cart;
use App\Modules\Cart\Models\CartItem;
use App\Modules\Catalog\Models\AttributeSet;
use App\Modules\Catalog\Models\Product;
use App\Modules\Customers\Models\Customer;
use App\Modules\Customers\Models\Wishlist;
use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Marketing\Models\Coupon;
use App\Modules\Shipping\Models\ShippingZone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Cart page: totals, coupon validation, delivery-area estimate, undo, save-for-later, clear, ownership. */
class StorefrontCartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // actingAs keeps one customer_id-keyed cart across requests (the test client does not
        // round-trip Set-Cookie, same approach as the other storefront tests).
        $this->actingAs(Customer::query()->create(['phone' => '+8801711111111', 'name' => 'টেস্ট']), 'customer');
    }

    private function variant(string $slug, int $price, int $stock = 10): \App\Modules\Catalog\Models\ProductVariant
    {
        $set = AttributeSet::query()->firstOrCreate(['code' => 'honey'], ['name' => 'Honey']);
        $product = Product::query()->create([
            'attribute_set_id' => $set->id, 'name' => ['bn' => 'মধু '.$slug], 'slug' => $slug, 'type' => 'simple', 'status' => 'active',
        ]);
        $variant = $product->variants()->create(['sku' => strtoupper($slug), 'price' => $price, 'is_default' => true]);
        InventoryItem::query()->create(['variant_id' => $variant->id, 'on_hand' => $stock]);

        return $variant;
    }

    private function zones(): array
    {
        $dhaka = ShippingZone::query()->create(['name' => 'Dhaka Metro', 'match' => ['keyword' => 'dhaka'], 'priority' => 10]);
        $dhaka->rates()->create(['method' => 'standard', 'fee' => 6000, 'free_over' => 100000]);
        $rest = ShippingZone::query()->create(['name' => 'Outside Dhaka', 'match' => ['default' => true], 'priority' => 0]);
        $rest->rates()->create(['method' => 'standard', 'fee' => 12000]);

        return [$dhaka, $rest];
    }

    public function test_empty_cart_shows_the_empty_state(): void
    {
        $this->get('/cart')->assertOk()->assertSee('আপনার কার্ট এখনো খালি!');
    }

    public function test_cart_shows_lines_totals_and_estimated_delivery_for_the_default_zone(): void
    {
        $this->zones();
        $v = $this->variant('honey-a', 50000); // ৳500

        $this->post('/cart/items', ['variant_id' => $v->id, 'qty' => 2]);

        $this->get('/cart')->assertOk()
            ->assertSee('মধু honey-a')
            ->assertSee('৳1,000')          // 2 × ৳500
            ->assertSee('ডেলিভারি চার্জ ফ্রি'); // Dhaka is free over ৳1,000
    }

    public function test_choosing_another_area_changes_the_delivery_fee_and_grand_total(): void
    {
        [, $rest] = $this->zones();
        $v = $this->variant('honey-b', 50000);
        $this->post('/cart/items', ['variant_id' => $v->id, 'qty' => 1]);

        $this->post('/cart/area', ['zone_id' => $rest->id])->assertRedirect();

        $this->get('/cart')->assertOk()->assertSee('৳120')->assertSee('৳620'); // 500 + 120
    }

    public function test_invalid_coupon_is_rejected_and_valid_one_applies_a_discount(): void
    {
        $this->zones();
        Coupon::query()->create(['code' => 'HONEY10', 'type' => 'percent', 'value' => 10, 'min_cart' => 100000, 'is_active' => true]);
        $v = $this->variant('honey-c', 150000); // ৳1,500
        $this->post('/cart/items', ['variant_id' => $v->id, 'qty' => 1]);

        $this->from('/cart')->post('/cart/coupon', ['code' => 'WRONG'])->assertSessionHasErrors('coupon');

        $this->post('/cart/coupon', ['code' => ' honey10 '])->assertSessionDoesntHaveErrors();
        $this->get('/cart')->assertOk()->assertSee('সফলভাবে প্রয়োগ করা হয়েছে')->assertSee('- ৳150');

        $this->delete('/cart/coupon');
        $this->get('/cart')->assertDontSee('সফলভাবে প্রয়োগ করা হয়েছে');
    }

    public function test_coupon_below_the_minimum_cart_is_refused(): void
    {
        Coupon::query()->create(['code' => 'BIG', 'type' => 'percent', 'value' => 10, 'min_cart' => 500000, 'is_active' => true]);
        $v = $this->variant('honey-d', 50000);
        $this->post('/cart/items', ['variant_id' => $v->id, 'qty' => 1]);

        $this->from('/cart')->post('/cart/coupon', ['code' => 'BIG'])->assertSessionHasErrors('coupon');
        $this->assertNull(Cart::query()->first()->coupon_code);
    }

    public function test_removing_a_line_offers_an_undo_that_restores_it(): void
    {
        $this->zones();
        $v = $this->variant('honey-e', 50000);
        $this->post('/cart/items', ['variant_id' => $v->id, 'qty' => 2]);
        $item = CartItem::query()->firstOrFail();

        $this->from('/cart')->delete('/cart/items/'.$item->id)->assertRedirect('/cart');
        $this->assertSame(0, CartItem::query()->count());

        $this->get('/cart')->assertOk()->assertSee('কার্ট থেকে সরানো হয়েছে')->assertSee('Undo');

        $this->post('/cart/items', ['variant_id' => $v->id, 'qty' => 2]);
        $this->assertSame(2, CartItem::query()->firstOrFail()->qty);
    }

    public function test_save_for_later_moves_the_line_to_the_wishlist(): void
    {
        $v = $this->variant('honey-f', 50000);
        $this->post('/cart/items', ['variant_id' => $v->id, 'qty' => 1]);
        $item = CartItem::query()->firstOrFail();

        $this->post('/cart/items/'.$item->id.'/save-later')->assertRedirect();

        $this->assertSame(0, CartItem::query()->count());
        $this->assertTrue(Wishlist::query()->where('product_id', $v->product_id)->exists());
    }

    public function test_clear_empties_the_cart(): void
    {
        $a = $this->variant('honey-g', 50000);
        $b = $this->variant('honey-h', 60000);
        $this->post('/cart/items', ['variant_id' => $a->id, 'qty' => 1]);
        $this->post('/cart/items', ['variant_id' => $b->id, 'qty' => 1]);

        $this->delete('/cart')->assertRedirect();

        $this->assertSame(0, CartItem::query()->count());
    }

    public function test_a_visitor_cannot_touch_someone_elses_cart_line(): void
    {
        $v = $this->variant('honey-i', 50000);
        $other = Customer::query()->create(['phone' => '+8801722222222']);
        $foreignCart = Cart::query()->create(['token' => 'someone-else', 'customer_id' => $other->id, 'currency' => 'BDT', 'expires_at' => now()->addDay()]);
        $foreign = $foreignCart->items()->create(['variant_id' => $v->id, 'qty' => 1, 'unit_price_snapshot' => 50000]);

        $this->patch('/cart/items/'.$foreign->id, ['qty' => 5])->assertNotFound();
        $this->delete('/cart/items/'.$foreign->id)->assertNotFound();
        $this->post('/cart/items/'.$foreign->id.'/save-later')->assertNotFound();

        $this->assertSame(1, $foreign->fresh()->qty);
    }
}
