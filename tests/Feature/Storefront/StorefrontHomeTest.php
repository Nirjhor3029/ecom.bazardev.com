<?php

namespace Tests\Feature\Storefront;

use App\Modules\Catalog\Models\AttributeSet;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Customers\Models\Customer;
use App\Modules\Inventory\Models\InventoryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Covers the dynamic Home page: real product-card links, add-to-cart, and category filtering. */
class StorefrontHomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_product_card_links_to_the_real_product_details_page(): void
    {
        $set = AttributeSet::query()->create(['code' => 'honey', 'name' => 'Honey']);
        $product = Product::query()->create([
            'attribute_set_id' => $set->id, 'name' => ['bn' => 'সরিষা ফুলের মধু'],
            'slug' => 'mustard-honey', 'type' => 'simple', 'status' => 'active',
        ]);
        $variant = $product->variants()->create(['sku' => 'WEB-2', 'price' => 58000, 'is_default' => true]);
        InventoryItem::query()->create(['variant_id' => $variant->id, 'on_hand' => 5]);

        $this->get('/')
            ->assertOk()
            ->assertSee('সরিষা ফুলের মধু')
            ->assertSee('/products/mustard-honey', false);
    }

    public function test_add_to_cart_from_the_home_grid_adds_the_real_variant(): void
    {
        $set = AttributeSet::query()->create(['code' => 'honey', 'name' => 'Honey']);
        $product = Product::query()->create([
            'attribute_set_id' => $set->id, 'name' => ['bn' => 'কালোজিরা মধু'],
            'slug' => 'kalojira-honey', 'type' => 'simple', 'status' => 'active',
        ]);
        $variant = $product->variants()->create(['sku' => 'WEB-3', 'price' => 82000, 'is_default' => true]);
        InventoryItem::query()->create(['variant_id' => $variant->id, 'on_hand' => 5]);

        // actingAs keeps one customer_id-keyed cart across these calls without
        // cookie plumbing — PHPUnit's test client doesn't round-trip Set-Cookie
        // between separate calls the way a real browser does (see
        // StorefrontCheckoutTest for the same note).
        $customer = Customer::query()->create(['phone' => '+8801955556666', 'phone_verified_at' => now()]);
        $this->actingAs($customer, 'customer');

        // The home page's add-to-cart form for this product must point at the real variant.
        $this->get('/')->assertOk()->assertSee((string) $variant->id, false);

        $this->post('/cart/items', ['variant_id' => $variant->id, 'qty' => 1])->assertRedirect();
        $this->get('/cart')->assertOk()->assertSee('কালোজিরা মধু');
    }

    public function test_category_link_filters_the_products_listing(): void
    {
        $set = AttributeSet::query()->create(['code' => 'honey', 'name' => 'Honey']);

        $honeyCategory = Category::query()->create(['name' => ['bn' => 'মধু'], 'slug' => 'honey', 'is_active' => true, 'sort' => 1]);
        $nutsCategory = Category::query()->create(['name' => ['bn' => 'বাদাম'], 'slug' => 'nuts', 'is_active' => true, 'sort' => 2]);

        $honeyProduct = Product::query()->create([
            'attribute_set_id' => $set->id, 'category_id' => $honeyCategory->id,
            'name' => ['bn' => 'খলিশা মধু'], 'slug' => 'khalisha-honey-cat', 'type' => 'simple', 'status' => 'active',
        ]);
        $honeyProduct->variants()->create(['sku' => 'WEB-4', 'price' => 75000, 'is_default' => true]);

        $nutsProduct = Product::query()->create([
            'attribute_set_id' => $set->id, 'category_id' => $nutsCategory->id,
            // Not "কাজুবাদাম" — the shared header's search placeholder hardcodes that
            // exact word as a sample suggestion, so it would false-positive on every page.
            'name' => ['bn' => 'আমন্ড বাদাম'], 'slug' => 'cashew-nuts-cat', 'type' => 'simple', 'status' => 'active',
        ]);
        $nutsProduct->variants()->create(['sku' => 'WEB-5', 'price' => 45000, 'is_default' => true]);

        $response = $this->get('/products?'.http_build_query(['filter' => ['category' => $honeyCategory->id]]));

        $response->assertOk()->assertSee('খলিশা মধু')->assertDontSee('আমন্ড বাদাম');
    }
}
