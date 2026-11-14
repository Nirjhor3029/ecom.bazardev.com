<?php

namespace App\Modules\Catalog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Http\Requests\Admin\StoreBrandRequest;
use App\Modules\Catalog\Http\Resources\Admin\BrandResource;
use App\Modules\Catalog\Models\Brand;
use App\Modules\Core\Support\SlugGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return $this->paginated(
            Brand::query()->orderBy('name')->paginate($request->integer('per_page', 20)),
            BrandResource::class,
        );
    }

    public function store(StoreBrandRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? SlugGenerator::unique('brands', $data['name']);

        $brand = Brand::query()->create($data);

        return response()->json(['data' => new BrandResource($brand)], 201);
    }

    public function show(Brand $brand): JsonResponse
    {
        return response()->json(['data' => new BrandResource($brand)]);
    }

    public function update(StoreBrandRequest $request, Brand $brand): JsonResponse
    {
        $brand->update($request->validated());

        return response()->json(['data' => new BrandResource($brand)]);
    }

    public function destroy(Brand $brand): JsonResponse
    {
        $brand->delete();

        return response()->json(status: 204);
    }
}
