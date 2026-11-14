<?php

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Attribute;
use App\Modules\Catalog\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * SRC-1/SRC-2: keyword search + filter/sort for the public catalog.
 * MySQL LIKE for now (A3 — a SearchEngine adapter + Meilisearch driver is
 * a later Phase 2 swap, SRC-5); not optimized for large catalogs yet.
 */
class ProductCatalogService
{
    public function search(Request $request): Builder
    {
        $query = Product::query()
            ->where('status', 'active')
            ->with(['variants.inventoryItem', 'media.media', 'attributeSet.attributeDefinitions']);

        if ($categoryId = $request->integer('filter.category')) {
            $query->where('category_id', $categoryId);
        }

        if ($brandId = $request->integer('filter.brand')) {
            $query->where('brand_id', $brandId);
        }

        if ($q = $request->string('q')->toString()) {
            $query->where(function (Builder $query) use ($q) {
                $query->whereRaw("JSON_EXTRACT(name, '$.bn') LIKE ?", ["%{$q}%"])
                    ->orWhereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ["%{$q}%"])
                    ->orWhereHas('variants', fn ($vq) => $vq->where('sku', 'like', "%{$q}%"));
            });
        }

        // filter[attr_code]=value — matches the SRS 3.4 `filter[x]=y` convention.
        foreach ((array) $request->input('filter', []) as $key => $value) {
            if (in_array($key, ['category', 'brand', 'price_min', 'price_max'], true)) {
                continue;
            }

            $query->whereRaw("JSON_EXTRACT(attributes, ?) = ?", ['$.'.$key, json_encode($value)]);
        }

        $this->applySort($query, $request->string('sort', '-created_at')->toString());

        return $query;
    }

    protected function applySort(Builder $query, string $sort): void
    {
        match ($sort) {
            'price_asc', 'price_desc' => null, // price lives on variants; handled client-side for now (TODO: denormalize min_price onto products for sortable queries)
            'newest', '-created_at' => $query->latest(),
            default => $query->latest(),
        };
    }

    /** FR-CAT-3: facets for every is_filterable, non-variant attribute in play. */
    public function facets(?int $categoryId = null): array
    {
        $productsQuery = Product::query()->where('status', 'active');

        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }

        $attributeSetIds = $productsQuery->pluck('attribute_set_id')->unique();

        $attributes = Attribute::query()
            ->whereIn('attribute_set_id', $attributeSetIds)
            ->where('is_filterable', true)
            ->get();

        $products = $productsQuery->with('variants')->get(['id', 'attributes']);

        return $attributes->map(function (Attribute $attr) use ($products) {
            $counts = [];

            foreach ($products as $product) {
                // is_variant attributes (e.g. weight) live on each variant's `options`,
                // not on the product row — count once per product that offers that value.
                $values = $attr->is_variant
                    ? $product->variants->pluck("options.{$attr->code}")->filter()->unique()
                    : collect([$product->attributes[$attr->code] ?? null])->filter();

                foreach ($values as $v) {
                    foreach ((array) $v as $single) {
                        $counts[$single] = ($counts[$single] ?? 0) + 1;
                    }
                }
            }

            return [
                'code' => $attr->code,
                'label' => $attr->label,
                'type' => $attr->type,
                'options' => collect($counts)->map(fn ($count, $value) => ['value' => $value, 'count' => $count])->values(),
            ];
        })->values()->all();
    }
}
