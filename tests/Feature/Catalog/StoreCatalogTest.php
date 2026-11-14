<?php

namespace Tests\Feature\Catalog;

use App\Modules\Catalog\Models\AttributeSet;
use App\Modules\Catalog\Models\Product;
use App\Modules\Inventory\Models\InventoryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_inactive_products_are_not_listed_or_viewable(): void
    {
        $set = AttributeSet::query()->create(['code' => 'honey', 'name' => 'Honey']);

        $product = Product::query()->create([
            'attribute_set_id' => $set->id,
            'name' => ['bn' => 'ড্রাফট প্রোডাক্ট'],
            'slug' => 'draft-product',
            'type' => 'simple',
            'status' => 'draft',
        ]);
        $variant = $product->variants()->create(['sku' => 'DFT-1', 'price' => 10000, 'is_default' => true]);
        InventoryItem::query()->create(['variant_id' => $variant->id, 'on_hand' => 10]);

        $this->getJson('/api/v1/store/products')->assertOk()->assertJsonCount(0, 'data');
        $this->getJson('/api/v1/store/products/draft-product')->assertStatus(404);
    }

    public function test_active_product_is_listed_with_correct_price_and_availability(): void
    {
        $set = AttributeSet::query()->create(['code' => 'honey', 'name' => 'Honey']);

        $product = Product::query()->create([
            'attribute_set_id' => $set->id,
            'name' => ['bn' => 'মধু', 'en' => 'Honey'],
            'slug' => 'khalisha-honey',
            'type' => 'simple',
            'status' => 'active',
        ]);
        $variant = $product->variants()->create(['sku' => 'HNY-1', 'price' => 50000, 'sale_price' => 45000, 'is_default' => true]);
        InventoryItem::query()->create(['variant_id' => $variant->id, 'on_hand' => 5, 'low_stock_threshold' => 10]);

        $this->getJson('/api/v1/store/products')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.price_range.min', 45000);

        $this->getJson('/api/v1/store/products/khalisha-honey')
            ->assertOk()
            ->assertJsonPath('data.variants.0.effective_price', 45000)
            ->assertJsonPath('data.variants.0.availability.status', 'low_stock')
            ->assertJsonPath('data.variants.0.availability.qty_available', 5);
    }
}
