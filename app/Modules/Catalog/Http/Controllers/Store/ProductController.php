<?php

namespace App\Modules\Catalog\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Http\Resources\Store\ProductListResource;
use App\Modules\Catalog\Http\Resources\Store\ProductResource;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Services\ProductCatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request, ProductCatalogService $catalog): JsonResponse
    {
        $paginator = $catalog->search($request)->paginate(min($request->integer('per_page', 20), 100));

        return $this->paginated($paginator, ProductListResource::class);
    }

    public function show(string $slug): JsonResponse
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('status', 'active')
            ->with(['variants.inventoryItem', 'media.media', 'attributeSet.attributeDefinitions'])
            ->firstOrFail();

        return response()->json(['data' => new ProductResource($product)]);
    }

    public function filters(Request $request, ProductCatalogService $catalog): JsonResponse
    {
        return response()->json(['data' => $catalog->facets($request->integer('category') ?: null)]);
    }
}
