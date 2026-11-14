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
        $products = $catalog->search($request)->paginate(12)->withQueryString();
        $categories = Category::query()->whereNull('parent_id')->where('is_active', true)->orderBy('sort')->get();
        $facets = $catalog->facets($request->integer('filter.category') ?: null);

        return view('storefront.products.index', compact('products', 'categories', 'facets'));
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

        return view('storefront.products.show', compact('product', 'related'));
    }
}
