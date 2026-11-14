<?php

namespace App\Modules\Catalog\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Services\ShopPageBuilder;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * PRD §6: the exact same ProductCatalogService the JSON API uses — no
 * HTTP call to /api/v1/store/products, same query builder, same rules.
 */
class ProductController extends Controller
{
    public function index(Request $request, ShopPageBuilder $shop): View
    {
        // The shop defaults to "popular"; the JSON API keeps its own default ordering.
        return view('storefront.products.index', $shop->build($request, 'products.index'));
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
