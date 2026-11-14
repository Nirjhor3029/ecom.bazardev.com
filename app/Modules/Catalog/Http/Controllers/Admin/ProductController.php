<?php

namespace App\Modules\Catalog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Actions\Product\AddVariantAction;
use App\Modules\Catalog\Actions\Product\CreateProductAction;
use App\Modules\Catalog\Actions\Product\RemoveVariantAction;
use App\Modules\Catalog\Actions\Product\UpdateProductAction;
use App\Modules\Catalog\Actions\Product\UpdateVariantAction;
use App\Modules\Catalog\Http\Requests\Admin\StoreProductRequest;
use App\Modules\Catalog\Http\Requests\Admin\StoreVariantRequest;
use App\Modules\Catalog\Http\Requests\Admin\UpdateProductRequest;
use App\Modules\Catalog\Http\Requests\Admin\UpdateVariantRequest;
use App\Modules\Catalog\Http\Resources\Admin\ProductResource;
use App\Modules\Catalog\Http\Resources\Admin\VariantResource;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('catalog.product.view');

        $query = Product::query()->with('variants')->latest();

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($q = $request->string('q')->toString()) {
            $query->where(function ($query) use ($q) {
                $query->whereRaw("JSON_EXTRACT(name, '$.bn') LIKE ?", ["%{$q}%"])
                    ->orWhereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ["%{$q}%"]);
            });
        }

        return $this->paginated($query->paginate($request->integer('per_page', 20)), ProductResource::class);
    }

    public function store(StoreProductRequest $request, CreateProductAction $action): JsonResponse
    {
        $product = $action->execute($request->validated());

        return response()->json(['data' => new ProductResource($product)], 201);
    }

    public function show(Product $product): JsonResponse
    {
        $this->authorize('catalog.product.view');

        return response()->json(['data' => new ProductResource($product->load('variants.inventoryItem'))]);
    }

    public function update(UpdateProductRequest $request, Product $product, UpdateProductAction $action): JsonResponse
    {
        $product = $action->execute($product, $request->validated());

        return response()->json(['data' => new ProductResource($product->load('variants'))]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('catalog.product.delete');

        $product->delete();

        return response()->json(status: 204);
    }

    public function storeVariant(StoreVariantRequest $request, Product $product, AddVariantAction $action): JsonResponse
    {
        $variant = $action->execute($product, $request->validated());

        return response()->json(['data' => new VariantResource($variant)], 201);
    }

    public function updateVariant(UpdateVariantRequest $request, Product $product, ProductVariant $variant, UpdateVariantAction $action): JsonResponse
    {
        $variant = $action->execute($variant, $request->validated());

        return response()->json(['data' => new VariantResource($variant)]);
    }

    public function destroyVariant(Product $product, ProductVariant $variant, RemoveVariantAction $action): JsonResponse
    {
        $this->authorize('catalog.product.update');

        $action->execute($variant);

        return response()->json(status: 204);
    }
}
