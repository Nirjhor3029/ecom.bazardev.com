<?php

namespace App\Modules\Catalog\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Services\ProductCatalogService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * PRD §6: the exact same ProductCatalogService the JSON API uses — no
 * HTTP call to /api/v1/store/products, same query builder, same rules.
 */
class ProductController extends Controller
{
    public function index(Request $request, ProductCatalogService $catalog): View
    {
        // The shop defaults to "popular"; the JSON API keeps its own default ordering.
        if (! $request->filled('sort')) {
            $request->merge(['sort' => 'popular']);
        }

        $products = $catalog->search($request)->paginate(12)->withQueryString();

        $categories = Category::query()
            ->whereNull('parent_id')->where('is_active', true)->orderBy('sort')
            ->withCount(['products as products_count' => fn ($q) => $q->where('status', 'active')])
            ->with('image')->get();
        $facets = $catalog->facets((int) (\Illuminate\Support\Arr::first(\Illuminate\Support\Arr::wrap($request->input('filter.category'))) ?: 0) ?: null);

        $active = Product::query()->where('status', 'active');
        $stats = [
            'total' => (clone $active)->count(),
            'avg' => round((float) \App\Modules\Catalog\Models\Review::query()->where('status', 'approved')->avg('rating'), 1),
            'in_stock' => (clone $active)->whereHas('variants.inventoryItem', fn ($q) => $q->whereRaw('on_hand - reserved > 0'))->count(),
        ];
        $ratingCounts = (clone $active)->withAvg('approvedReviews as avg', 'rating')->get()
            ->pipe(fn ($all) => [5 => $all->where('avg', '>=', 5)->count(), 4 => $all->where('avg', '>=', 4)->count()]);
        $bounds = [
            'min' => (int) floor((\App\Modules\Catalog\Models\ProductVariant::query()->where('is_active', true)->min(\Illuminate\Support\Facades\DB::raw('COALESCE(sale_price, price)')) ?? 0) / 100),
            'max' => (int) ceil((\App\Modules\Catalog\Models\ProductVariant::query()->where('is_active', true)->max('price') ?? 0) / 100),
        ];

        // Active filter chips, each with a URL that removes just that filter.
        $query = $request->query();
        $without = function (array $drop) use ($query) {
            $q = $query;
            foreach ($drop as $key) {
                \Illuminate\Support\Arr::forget($q, $key);
            }
            unset($q['page']);

            return route('products.index', $q);
        };
        $chips = [];
        foreach (\Illuminate\Support\Arr::wrap($request->input('filter.category')) as $id) {
            if ($cat = $categories->firstWhere('id', (int) $id)) {
                $rest = array_values(array_diff(\Illuminate\Support\Arr::wrap($request->input('filter.category')), [$id]));
                $q = $query;
                unset($q['page']);
                $rest ? \Illuminate\Support\Arr::set($q, 'filter.category', $rest) : \Illuminate\Support\Arr::forget($q, 'filter.category');
                $chips[] = ['label' => $cat->name['bn'] ?? '', 'url' => route('products.index', $q)];
            }
        }
        if ($request->boolean('in_stock')) {
            $chips[] = ['label' => 'ইন স্টক', 'url' => $without(['in_stock'])];
        }
        if ($request->filled('price_min') || $request->filled('price_max')) {
            $chips[] = ['label' => '৳'.($request->integer('price_min') ?: $bounds['min']).' - ৳'.($request->integer('price_max') ?: $bounds['max']), 'url' => $without(['price_min', 'price_max'])];
        }
        if ($request->integer('rating_min')) {
            $chips[] = ['label' => $request->integer('rating_min').'★ ও তদূর্ধ্ব', 'url' => $without(['rating_min'])];
        }
        if ($q = $request->string('q')->toString()) {
            $chips[] = ['label' => '“'.$q.'”', 'url' => $without(['q'])];
        }

        return view('storefront.products.index', [
            'products' => $products, 'categories' => $categories, 'facets' => $facets,
            'stats' => $stats, 'ratingCounts' => $ratingCounts, 'bounds' => $bounds,
            'chips' => $chips, 'sort' => $request->string('sort')->toString(),
        ]);
    }

    public function show(string $slug): View
    {
        $product = Product::query()
            ->where('slug', $slug)->where('status', 'active')
            ->with(['variants.inventoryItem', 'media.media', 'attributeSet.attributeDefinitions', 'category'])
            ->firstOrFail();

        $related = Product::query()
            ->where('status', 'active')->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['variants.inventoryItem', 'media.media'])
            ->limit(4)->get();

        $rating = $product->ratingSummary();
        $reviews = $product->reviews()->with('customer')->limit(6)->get();

        return view('storefront.products.show', compact('product', 'related', 'rating', 'reviews'));
    }
}
