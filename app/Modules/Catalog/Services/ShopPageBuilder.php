<?php

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductVariant;
use App\Modules\Catalog\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * Everything the storefront listing pages (shop + search) need on top of the
 * raw ProductCatalogService query: category counts, sidebar stats, price bounds,
 * and the "active filter" chips with their remove-URLs.
 */
class ShopPageBuilder
{
    public function __construct(protected ProductCatalogService $catalog) {}

    /**
     * @param  string  $route  route name the page lives on (chip/clear URLs are built against it)
     * @param  string  $defaultSort  sort used when the request has none
     */
    public function build(Request $request, string $route, string $defaultSort = 'popular'): array
    {
        if (! $request->filled('sort')) {
            $request->merge(['sort' => $defaultSort]);
        }

        $products = $this->catalog->search($request)->paginate(12)->withQueryString();

        $categories = Category::query()
            ->whereNull('parent_id')->where('is_active', true)->orderBy('sort')
            ->withCount(['products as products_count' => fn ($q) => $q->where('status', 'active')])
            ->with('image')->get();

        // On the search page the per-category numbers describe the *matches*, not the whole catalog.
        if ($request->filled('q')) {
            $matches = $this->catalog->search(Request::create('/', 'GET', ['q' => $request->string('q')->toString()]))->get(['products.id', 'category_id']);
            $byCat = $matches->groupBy('category_id')->map->count();
            $categories->each(fn (Category $c) => $c->products_count = (int) ($byCat[$c->id] ?? 0));
        }

        $facets = $this->catalog->facets((int) (Arr::first(Arr::wrap($request->input('filter.category'))) ?: 0) ?: null);

        $active = Product::query()->where('status', 'active');
        $stats = [
            'total' => (clone $active)->count(),
            'avg' => round((float) Review::query()->where('status', 'approved')->avg('rating'), 1),
            'in_stock' => (clone $active)->whereHas('variants.inventoryItem', fn ($q) => $q->whereRaw('on_hand - reserved > 0'))->count(),
        ];
        $ratingCounts = (clone $active)->withAvg('approvedReviews as avg', 'rating')->get()
            ->pipe(fn ($all) => [5 => $all->where('avg', '>=', 5)->count(), 4 => $all->where('avg', '>=', 4)->count()]);
        $bounds = [
            'min' => (int) floor((ProductVariant::query()->where('is_active', true)->min(DB::raw('COALESCE(sale_price, price)')) ?? 0) / 100),
            'max' => (int) ceil((ProductVariant::query()->where('is_active', true)->max('price') ?? 0) / 100),
        ];

        return [
            'products' => $products, 'categories' => $categories, 'facets' => $facets,
            'stats' => $stats, 'ratingCounts' => $ratingCounts, 'bounds' => $bounds,
            'chips' => $this->chips($request, $route, $categories, $bounds),
            'sort' => $request->string('sort')->toString(),
            'formAction' => route($route),
        ];
    }

    /** @return list<array{label: string, url: string}> */
    protected function chips(Request $request, string $route, $categories, array $bounds): array
    {
        $query = $request->query();
        $url = function (array $q) use ($route) {
            unset($q['page']);

            return route($route, $q);
        };
        $without = function (array $drop) use ($query, $url) {
            $q = $query;
            foreach ($drop as $key) {
                Arr::forget($q, $key);
            }

            return $url($q);
        };

        $chips = [];
        $selected = Arr::wrap($request->input('filter.category'));
        foreach ($selected as $id) {
            if ($cat = $categories->firstWhere('id', (int) $id)) {
                $q = $query;
                $rest = array_values(array_diff($selected, [$id]));
                $rest ? Arr::set($q, 'filter.category', $rest) : Arr::forget($q, 'filter.category');
                $chips[] = ['label' => $cat->name['bn'] ?? '', 'url' => $url($q)];
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
        if (($term = $request->string('q')->toString()) !== '' && $route === 'products.index') {
            $chips[] = ['label' => '“'.$term.'”', 'url' => $without(['q'])];
        }

        return $chips;
    }
}
