<?php

namespace Tests\Feature\Storefront;

use App\Modules\Catalog\Models\AttributeSet;
use App\Modules\Catalog\Models\Product;
use App\Modules\Customers\Models\Customer;
use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Shipping\Models\ShippingZone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/** Checkout page helpers: live delivery quote, inline OTP (JSON), saved-address prefill. */
class StorefrontCheckoutQuoteTest extends TestCase
{
    use RefreshDatabase;

    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $set = AttributeSet::query()->create(['code' => 'honey', 'name' => 'Honey']);
        $product = Product::query()->create([
            'attribute_set_id' => $set->id, 'name' => ['bn' => 'টেস্ট মধু'], 'slug' => 'q-honey', 'type' => 'simple', 'status' => 'active',
        ]);
        $variant = $product->variants()->create(['sku' => 'Q-1', 'price' => 40000, 'is_default' => true]);
        InventoryItem::query()->create(['variant_id' => $variant->id, 'on_hand' => 9]);

        $zone = ShippingZone::query()->create(['name' => 'Dhaka', 'match' => ['keyword' => 'dhaka'], 'priority' => 10]);
        $zone->rates()->create(['fee' => 6000, 'est_days_min' => 1, 'est_days_max' => 2]);

        $this->customer = Customer::query()->create(['phone' => '+8801911112222', 'phone_verified_at' => now()]);
        $this->actingAs($this->customer, 'customer');
        $this->post('/cart/items', ['variant_id' => $variant->id, 'qty' => 1]);
    }

    public function test_quote_returns_the_fee_eta_and_grand_total_for_a_serviceable_district(): void
    {
        $this->getJson('/checkout/quote?district=Dhaka')
            ->assertOk()
            ->assertJsonPath('serviceable', true)
            ->assertJsonPath('delivery_fee', 6000)
            ->assertJsonPath('grand_total', 46000)
            ->assertJsonPath('est_days_min', 1);
    }

    public function test_quote_says_so_when_the_area_is_not_serviceable(): void
    {
        $this->getJson('/checkout/quote?district=Sylhet')
            ->assertStatus(422)
            ->assertJsonPath('serviceable', false);
    }

    public function test_inline_otp_send_and_verify_work_over_json(): void
    {
        auth('customer')->logout();

        $this->postJson('/checkout/otp/send', ['phone' => '01733445566'])
            ->assertOk()->assertJsonPath('ok', true)->assertJsonPath('phone', '+8801733445566');

        $code = Cache::get('otp:+8801733445566')['code'];

        $this->postJson('/checkout/otp/verify', ['code' => '000000'])->assertStatus(422)->assertJsonPath('ok', false);
        $this->postJson('/checkout/otp/verify', ['code' => $code])->assertOk()->assertJsonPath('ok', true);

        $this->assertAuthenticated('customer');
    }

    public function test_invalid_phone_is_rejected_with_a_json_error(): void
    {
        $this->postJson('/checkout/otp/send', ['phone' => '12345'])->assertStatus(422)->assertJsonPath('ok', false);
    }
}
