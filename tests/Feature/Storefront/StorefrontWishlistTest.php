<?php

namespace Tests\Feature\Storefront;

use App\Modules\Catalog\Models\AttributeSet;
use App\Modules\Catalog\Models\Product;
use App\Modules\Customers\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * MKT-6: wishlist toggle must actually add/remove, both for a logged-in
 * customer (DB-backed) and a guest (session-backed). Uses the empty-state
 * marker text rather than the product name — this demo product's real
 * name ("সুন্দরবনের খলিশা মধু") is also the header search bar's hardcoded
 * placeholder example, so a name-based assertSee() is always true.
 */
class StorefrontWishlistTest extends TestCase
{
    use RefreshDatabase;

    protected function product(): Product
    {
        $set = AttributeSet::query()->create(['code' => 'honey', 'name' => 'Honey']);
        $product = Product::query()->create([
            'attribute_set_id' => $set->id, 'name' => ['bn' => 'সুন্দরবনের খলিশা মধু'],
            'slug' => 'sundarban-khalisha-honey', 'type' => 'simple', 'status' => 'active',
        ]);
        $product->variants()->create(['sku' => 'WEB-WL-1', 'price' => 75000, 'is_default' => true]);

        return $product;
    }

    public function test_logged_in_customer_can_toggle_a_product_on_and_off_the_wishlist(): void
    {
        $product = $this->product();
        $customer = Customer::query()->create(['phone' => '+8801911112233', 'phone_verified_at' => now()]);
        $this->actingAs($customer, 'customer');

        $this->get('/wishlist')->assertOk()->assertSee('আপনার উইশলিস্ট খালি');

        $this->post(route('wishlist.toggle', $product))->assertRedirect();
        $this->get('/wishlist')->assertOk()->assertDontSee('আপনার উইশলিস্ট খালি');
        $this->assertDatabaseHas('wishlists', ['customer_id' => $customer->id, 'product_id' => $product->id]);

        $this->post(route('wishlist.toggle', $product))->assertRedirect();
        $this->get('/wishlist')->assertOk()->assertSee('আপনার উইশলিস্ট খালি');
        $this->assertDatabaseMissing('wishlists', ['customer_id' => $customer->id, 'product_id' => $product->id]);
    }

    public function test_guest_can_toggle_a_product_on_and_off_the_session_wishlist(): void
    {
        $product = $this->product();

        $this->withSession(['wishlist' => []])
            ->post(route('wishlist.toggle', $product))
            ->assertRedirect();
        $this->assertSame([$product->id], session('wishlist'));

        $this->withSession(['wishlist' => [$product->id]])
            ->post(route('wishlist.toggle', $product))
            ->assertRedirect();
        $this->assertSame([], session('wishlist'));
    }
}
