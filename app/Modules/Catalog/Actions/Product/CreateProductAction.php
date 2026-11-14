<?php

namespace App\Modules\Catalog\Actions\Product;

use App\Modules\Catalog\Models\Product;
use App\Modules\Core\Support\SlugGenerator;
use Illuminate\Support\Facades\DB;

class CreateProductAction
{
    public function execute(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $variants = $data['variants'] ?? [];
            unset($data['variants']);

            $data['slug'] = $data['slug'] ?? SlugGenerator::unique('products', $data['name']['en'] ?? $data['name']['bn']);
            $data['status'] = $data['status'] ?? 'draft';

            $product = Product::query()->create($data);

            foreach ($variants as $i => $variantData) {
                app(AddVariantAction::class)->execute($product, array_merge(
                    $variantData,
                    // first variant defaults to the default one if none specified
                    $i === 0 && ! array_key_exists('is_default', $variantData) ? ['is_default' => true] : [],
                ));
            }

            return $product->load('variants');
        });
    }
}
