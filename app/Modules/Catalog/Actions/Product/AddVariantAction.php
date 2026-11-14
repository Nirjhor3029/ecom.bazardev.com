<?php

namespace App\Modules\Catalog\Actions\Product;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductVariant;
use App\Modules\Inventory\Models\InventoryItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AddVariantAction
{
    /** FR-CAT-4: option combination must be unique within the product. */
    public function execute(Product $product, array $data): ProductVariant
    {
        $options = $data['options'] ?? [];

        // Compare decoded arrays in PHP, not raw JSON strings: MySQL's JSON
        // column re-serializes with different spacing than json_encode().
        if ($options && $product->variants->contains(fn (ProductVariant $v) => ($v->options ?? []) === $options)) {
            throw ValidationException::withMessages([
                'options' => ['A variant with this exact option combination already exists on this product.'],
            ]);
        }

        return DB::transaction(function () use ($product, $data) {
            if (! empty($data['is_default'])) {
                $product->variants()->update(['is_default' => false]);
            }

            $variant = $product->variants()->create($data);

            InventoryItem::query()->create([
                'variant_id' => $variant->id,
                'on_hand' => 0,
                'reserved' => 0,
                'low_stock_threshold' => 5,
                'track_stock' => true,
                'backorder_policy' => 'deny',
            ]);

            return $variant;
        });
    }
}
