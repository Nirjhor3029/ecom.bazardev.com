<?php

namespace Tests\Feature\Storefront;

use App\Modules\Catalog\Models\AttributeSet;
use App\Modules\Catalog\Models\Product;
use App\Modules\Customers\Models\Customer;
use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Orders\Models\Order;
use App\Modules\Shipping\Models\ShippingZone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** PRD §6: the storefront must work end to end on the Blade routes, no JSON API involved. */
class StorefrontCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $set = AttributeSet::query()->create(['code' => 'honey', 'name' => 'Honey']);
        $product = Product::query()->create([
            'attribute_set_id' => $set->id, 'name' => ['bn' => 'টেস্ট মধু'],
            'slug' => 'test-honey', 'type' => 'simple', 'status' => 'active',
        ]);
        // IDs are not reset between tests (MySQL auto_increment survives a
        // RefreshDatabase transaction rollback) — always reference $this->variant->id,
        // never a hardcoded 1.
        $this->variant = $product->variants()->create(['sku' => 'WEB-1', 'price' => 40000, 'is_default' => true]);
        InventoryItem::query()->create(['variant_id' => $this->variant->id, 'on_hand' => 5]);

        $zone = ShippingZone::query()->create(['name' => 'Dhaka', 'match' => ['keyword' => 'dhaka'], 'priority' => 10]);
        $zone->rates()->create(['fee' => 6000, 'est_days_min' => 1, 'est_days_max' => 2]);
    }

    public function test_logged_in_customer_can_browse_add_to_cart_and_place_a_cod_order(): void
    {
        // actingAs keeps a customer_id-keyed cart across requests without any
        // cookie plumbing — the cookie-based guest-cart path is covered by the
        // live manual walkthrough (a real browser round-trips Set-Cookie
        // correctly; PHPUnit's test client does not do that automatically).
        $customer = Customer::query()->create(['phone' => '+8801911112222', 'phone_verified_at' => now()]);
        $this->actingAs($customer, 'customer');

        $this->get('/products/test-honey')->assertOk()->assertSee('টেস্ট মধু');

        $this->post('/cart/items', ['variant_id' => $this->variant->id, 'qty' => 1])->assertRedirect();
        $this->get('/cart')->assertOk()->assertSee('টেস্ট মধু');

        $quote = $this->get('/checkout?district=Dhaka')->assertOk();
        $quote->assertSee('অর্ডার কনফার্ম');

        $response = $this->post('/checkout/place', [
            'district' => 'Dhaka', 'address_line' => 'House 1', 'name' => 'Web Tester',
            'phone' => '01911112222', 'payment_method' => 'cod', 'expected_total' => 46000,
        ]);

        $response->assertRedirect();
        $this->assertStringContainsString('/success', $response->headers->get('Location'));
        $this->assertSame(1, Order::query()->count());
    }

    public function test_guest_checkout_requires_phone_verification_first(): void
    {
        // Build the cart directly rather than through the HTTP add-to-cart
        // call: TestResponse::withCookie() encrypts its value expecting the
        // normal EncryptCookies middleware to undo it on the next request,
        // which only lines up cleanly when the cart is created in-process.
        $cartService = app(\App\Modules\Cart\Services\CartService::class);
        $cart = $cartService->getOrCreate(null);
        $cartService->addItem($cart, $this->variant->id, 1);

        // No `customer` session -> address/payment form must not render.
        $this->withCookie(\App\Modules\Cart\Support\StorefrontCart::COOKIE, $cart->token)
            ->get('/checkout')->assertOk()->assertDontSee('অর্ডার কনফার্ম')->assertSee('OTP পাঠান');
    }
}
