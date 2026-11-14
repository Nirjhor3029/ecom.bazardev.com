<?php

namespace Tests\Feature\Storefront;

use App\Modules\Catalog\Models\AttributeSet;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Inventory\Models\InventoryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Search results page, filters/sort over the term, and the live-suggest JSON endpoint. */
class StorefrontSearchTest extends TestCase
{
    use RefreshDatabase;

    private function product(string $bn, string $slug, int $price, ?Category $category = null, int $stock = 5): Product
    {
        $set = AttributeSet::query()->firstOrCreate(['code' => 'honey'], ['name' => 'Honey']);
        $product = Product::query()->create([
            'attribute_set_id' => $set->id, 'category_id' => $category?->id, 'name' => ['bn' => $bn],
            'slug' => $slug, 'type' => 'simple', 'status' => 'active',
        ]);
        $variant = $product->variants()->create(['sku' => strtoupper($slug), 'price' => $price, 'is_default' => true]);
        InventoryItem::query()->create(['variant_id' => $variant->id, 'on_hand' => $stock]);

        return $product;
    }

    /** Only the results grid — the page also has suggestion/discovery panels that may mention other products. */
    private function grid(string $html): string
    {
        $start = strpos($html, 'id="shop-grid"');

        return $start === false ? '' : substr($html, $start, (strpos($html, 'Promo banner', $start) ?: strlen($html)) - $start);
    }

    public function test_search_without_a_term_redirects_to_the_shop(): void
    {
        $this->get('/search')->assertRedirect(route('products.index'));
    }

    public function test_results_page_lists_only_matching_products_with_the_term_in_the_title(): void
    {
        $this->product('সুন্দরবনের খলিশা মধু', 'khalisha', 75000);
        $this->product('রোস্টেড কাজুবাদাম', 'cashew', 45000);

        $response = $this->get('/search?q='.urlencode('খলিশা'))->assertOk()->assertSee('এর ফলাফল');
        $grid = $this->grid($response->getContent());

        $this->assertStringContainsString('/products/khalisha', $grid);
        $this->assertStringNotContainsString('/products/cashew', $grid);
    }

    public function test_no_match_shows_the_empty_state(): void
    {
        $this->product('খলিশা মধু', 'khalisha', 75000);

        $this->get('/search?q=zzzz')
            ->assertOk()
            ->assertSee('কোনো পণ্য খুঁজে পাওয়া যায়নি');
    }

    public function test_price_filter_and_sort_apply_on_top_of_the_search_term(): void
    {
        $this->product('মধু সস্তা', 'cheap-honey', 30000);
        $this->product('মধু দামি', 'pricey-honey', 90000);

        $grid = $this->grid($this->get('/search?q='.urlencode('মধু').'&price_max=500')->assertOk()->getContent());
        $this->assertStringContainsString('/products/cheap-honey', $grid);
        $this->assertStringNotContainsString('/products/pricey-honey', $grid);

        $grid = $this->grid($this->get('/search?q='.urlencode('মধু').'&sort=price_desc')->getContent());
        $this->assertLessThan(strpos($grid, '/products/cheap-honey'), strpos($grid, '/products/pricey-honey'));
    }

    public function test_suggest_endpoint_returns_products_and_matching_categories(): void
    {
        $cat = Category::query()->create(['name' => ['bn' => 'খাঁটি মধু'], 'slug' => 'honey', 'is_active' => true]);
        $this->product('খলিশা মধু', 'khalisha', 75000, $cat);

        $this->getJson('/search/suggest?q='.urlencode('মধু'))
            ->assertOk()
            ->assertJsonPath('total', 1)
            ->assertJsonPath('products.0.name', 'খলিশা মধু')
            ->assertJsonPath('products.0.price', '৳750')
            ->assertJsonPath('categories.0.name', 'খাঁটি মধু');

        $this->getJson('/search/suggest?q=')->assertOk()->assertJsonPath('total', 0);
    }
}
