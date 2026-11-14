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
use App\Modules\Catalog\Models\ProductMedia;
use App\Modules\Catalog\Models\ProductVariant;
use App\Modules\Core\Models\Media;
use App\Modules\Core\Services\MediaService;
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

        return response()->json(['data' => new ProductResource($product->load('variants.inventoryItem', 'media.media'))]);
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

    /** Attach an existing gallery item, or upload a new file and attach it in one step. */
    public function storeMedia(Request $request, Product $product, MediaService $mediaService): JsonResponse
    {
        $this->authorize('catalog.product.update');

        if ($request->hasFile('file')) {
            $request->validate(['file' => ['required', 'file', 'image', 'max:20480']]);
            $media = $mediaService->upload($request->file('file'), $request->user()->id);
        } else {
            $request->validate(['media_id' => ['required', 'integer', 'exists:media,id']]);
            $media = Media::query()->findOrFail($request->integer('media_id'));
        }

        $isFirst = ! $product->media()->exists();

        $productMedia = $product->media()->create([
            'media_id' => $media->id,
            'is_primary' => $isFirst,
            'sort' => $product->media()->count(),
        ]);

        return response()->json(['data' => [
            'id' => $productMedia->id,
            'media_id' => $media->id,
            'url' => $media->url(),
            'is_primary' => $productMedia->is_primary,
        ]], 201);
    }

    public function updateMedia(Request $request, Product $product, ProductMedia $media): JsonResponse
    {
        $this->authorize('catalog.product.update');

        if ($request->boolean('is_primary')) {
            $product->media()->update(['is_primary' => false]);
            $media->update(['is_primary' => true]);
        }

        return response()->json(['data' => ['id' => $media->id, 'is_primary' => $media->refresh()->is_primary]]);
    }

    public function destroyMedia(Product $product, ProductMedia $media): JsonResponse
    {
        $this->authorize('catalog.product.update');

        $media->delete();

        return response()->json(status: 204);
    }
}
