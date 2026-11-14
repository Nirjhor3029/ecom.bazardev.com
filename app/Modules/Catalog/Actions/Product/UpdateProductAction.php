<?php

namespace App\Modules\Catalog\Actions\Product;

use App\Modules\Catalog\Models\Product;
use Illuminate\Validation\ValidationException;

class UpdateProductAction
{
    /**
     * FR-CAT-2: activating a product requires every is_required attribute
     * filled, a default variant present, and that variant's price > 0.
     */
    public function execute(Product $product, array $data): Product
    {
        $becomingActive = ($data['status'] ?? null) === 'active' && $product->status !== 'active';

        if ($becomingActive) {
            $this->assertCanActivate($product, $data);
        }

        $product->update($data);

        return $product->refresh();
    }

    protected function assertCanActivate(Product $product, array $data): void
    {
        $attributeSetId = $data['attribute_set_id'] ?? $product->attribute_set_id;
        $attributeValues = $data['attributes'] ?? $product->attributes ?? [];

        $requiredCodes = \App\Modules\Catalog\Models\Attribute::query()
            ->where('attribute_set_id', $attributeSetId)
            ->where('is_required', true)
            ->where('is_variant', false)
            ->pluck('code');

        $missing = $requiredCodes->filter(fn ($code) => empty($attributeValues[$code]))->values();

        if ($missing->isNotEmpty()) {
            throw ValidationException::withMessages([
                'attributes' => ["Required attributes missing: {$missing->implode(', ')}"],
            ]);
        }

        $defaultVariant = $product->variants()->where('is_default', true)->first()
            ?? $product->variants()->first();

        if (! $defaultVariant) {
            throw ValidationException::withMessages([
                'status' => ['Cannot activate — product has no variant yet.'],
            ]);
        }

        if ($defaultVariant->price <= 0) {
            throw ValidationException::withMessages([
                'status' => ['Cannot activate — the default variant has no price.'],
            ]);
        }
    }
}
