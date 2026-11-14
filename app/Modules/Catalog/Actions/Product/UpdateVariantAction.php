<?php

namespace App\Modules\Catalog\Actions\Product;

use App\Modules\Catalog\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class UpdateVariantAction
{
    public function execute(ProductVariant $variant, array $data): ProductVariant
    {
        return DB::transaction(function () use ($variant, $data) {
            if (! empty($data['is_default'])) {
                $variant->product->variants()->where('id', '!=', $variant->id)->update(['is_default' => false]);
            }

            $variant->update($data);

            return $variant->refresh();
        });
    }
}
