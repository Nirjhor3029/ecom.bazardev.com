<?php

namespace App\Modules\Catalog\Actions\Product;

use App\Modules\Catalog\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class RemoveVariantAction
{
    /** FR-CAT-4: a variant already referenced by order_items can't be hard-deleted — soft-disable it. */
    public function execute(ProductVariant $variant): void
    {
        $hasOrderHistory = DB::table('order_items')->where('variant_id', $variant->id)->exists();

        if ($hasOrderHistory) {
            $variant->update(['is_active' => false]);

            return;
        }

        $variant->delete();
    }
}
