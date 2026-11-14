<?php

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Attribute;
use App\Modules\Catalog\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Catalog\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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
            ->select('products.*')
            ->where('status', 'active')
            ->with(['variants.inventoryItem', 'media.media', 'attributeSet.attributeDefinitions']);

        // filter[category]=1 or filter[category][]=1&filter[category][]=2 (storefront multi-select).
        if ($categoryIds = array_filter(array_map('intval', Arr::wrap($request->input('filter.category'))))) {
            $query->whereIn('category_id', $categoryIds);
        }

        // Aggregates used by sorting and by the shop cards (rating pill, "best seller").
        $query->withCount('approvedReviews as reviews_count')->withAvg('approvedReviews as reviews_avg', 'rating');

        // Cheapest sellable price per product, in paisa (sale price wins when set).
        $query->addSelect(['min_price' => ProductVariant::query()
            ->selectRaw('MIN(COALESCE(sale_price, price))')
            ->whereColumn('product_id', 'products.id')->where('is_active', true)]);

        if ($request->filled('price_min')) {
            $query->having('min_price', '>=', $request->integer('price_min') * 100);
        }

        if ($request->filled('price_max')) {
            $query->having('min_price', '<=', $request->integer('price_max') * 100);
        }

        if ($request->boolean('in_stock')) {
            $query->whereHas('variants.inventoryItem', fn (Builder $q) => $q->whereRaw('on_hand - reserved > 0'));
        }

        if ($ratingMin = $request->integer('rating_min')) {
            $query->having('reviews_avg', '>=', $ratingMin);
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

            // Variant-level attributes (e.g. weight) live on product_variants.options, not on the product.
            if (Attribute::query()->where('code', $key)->where('is_variant', true)->exists()) {
                $query->whereHas('variants', fn (Builder $q) => $q->where("options->{$key}", $value));

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
            'price_asc' => $query->orderBy('min_price')->latest(),
            'price_desc' => $query->orderByDesc('min_price')->latest(),
            'rating' => $query->orderByDesc('reviews_avg')->orderByDesc('reviews_count')->latest(),
            'popular' => $query->orderByDesc('reviews_count')->latest(),
            default => $query->latest(), // newest / -created_at
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
