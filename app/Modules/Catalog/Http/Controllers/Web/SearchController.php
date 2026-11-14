<?php

namespace App\Modules\Catalog\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Services\ProductCatalogService;
use App\Modules\Catalog\Services\ShopPageBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Storefront search: a results page (same query/filter engine as the shop and the JSON API)
 * plus a tiny JSON endpoint that powers the live suggestions under the header search box.
 */
class SearchController extends Controller
{
    public function index(Request $request, ShopPageBuilder $shop, ProductCatalogService $catalog): View|RedirectResponse
    {
        $term = trim($request->string('q')->toString());

        if ($term === '') {
            return redirect()->route('products.index');
        }

        $request->merge(['q' => $term]);
        $data = $shop->build($request, 'search', 'relevance');

        // Best matches for the "smart suggestions" panel (term only — ignores sidebar filters).
        $top = $catalog->search(Request::create('/', 'GET', ['q' => $term, 'sort' => 'relevance']))->limit(5)->get();

        // Popular products to browse when the results are thin or empty.
        $discover = $catalog->search(Request::create('/', 'GET', ['sort' => 'popular', 'in_stock' => 1]))->limit(4)->get();

        $popularTerms = $discover->take(3)->map(fn (Product $p) => $p->name['bn'] ?? '')
            ->merge($data['categories']->take(2)->map(fn (Category $c) => $c->name['bn'] ?? ''))
            ->filter()->unique()->values();

        return view('storefront.search', $data + [
            'term' => $term, 'top' => $top, 'discover' => $discover, 'popularTerms' => $popularTerms,
        ]);
    }

    public function suggest(Request $request, ProductCatalogService $catalog): JsonResponse
    {
        $term = trim($request->string('q')->toString());

        if (mb_strlen($term) < 1) {
            return response()->json(['products' => [], 'categories' => [], 'total' => 0, 'url' => route('products.index')]);
        }

        $query = $catalog->search(Request::create('/', 'GET', ['q' => $term, 'sort' => 'relevance']));
        $total = (clone $query)->toBase()->getCountForPagination();
        $products = $query->limit(5)->get()->map(function (Product $p) {
            $range = $p->priceRange();

            return [
                'name' => $p->name['bn'] ?? $p->name['en'] ?? '',
                'url' => route('products.show', $p->slug),
                'image' => $p->primaryImageUrl(),
                'price' => '৳'.number_format($range['min'] / 100, 0),
                'meta' => $p->category?->name['bn'] ?? null,
            ];
        })->values();

        $categories = Category::query()->where('is_active', true)->whereNull('parent_id')
            ->whereRaw("JSON_EXTRACT(name, '$.bn') LIKE ?", ["%{$term}%"])->limit(3)->get()
            ->map(fn (Category $c) => ['name' => $c->name['bn'] ?? '', 'url' => route('products.index', ['filter' => ['category' => $c->id]])]);

        return response()->json([
            'products' => $products, 'categories' => $categories, 'total' => $total,
            'url' => route('search', ['q' => $term]),
        ]);
    }
}
