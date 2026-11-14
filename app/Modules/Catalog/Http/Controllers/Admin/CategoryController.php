<?php

namespace App\Modules\Catalog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Actions\Category\CreateCategoryAction;
use App\Modules\Catalog\Actions\Category\DeleteCategoryAction;
use App\Modules\Catalog\Actions\Category\UpdateCategoryAction;
use App\Modules\Catalog\Http\Requests\Admin\StoreCategoryRequest;
use App\Modules\Catalog\Http\Requests\Admin\UpdateCategoryRequest;
use App\Modules\Catalog\Http\Resources\Admin\CategoryResource;
use App\Modules\Catalog\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('catalog.category.view');

        return $this->paginated(
            Category::query()->orderBy('sort')->paginate($request->integer('per_page', 20)),
            CategoryResource::class,
        );
    }

    public function store(StoreCategoryRequest $request, CreateCategoryAction $action): JsonResponse
    {
        $category = $action->execute($request->validated());

        return response()->json(['data' => new CategoryResource($category)], 201);
    }

    public function show(Category $category): JsonResponse
    {
        $this->authorize('catalog.category.view');

        return response()->json(['data' => new CategoryResource($category)]);
    }

    public function update(UpdateCategoryRequest $request, Category $category, UpdateCategoryAction $action): JsonResponse
    {
        $category = $action->execute($category, $request->validated());

        return response()->json(['data' => new CategoryResource($category)]);
    }

    public function destroy(Category $category, DeleteCategoryAction $action): JsonResponse
    {
        $this->authorize('catalog.category.delete');

        $action->execute($category);

        return response()->json(status: 204);
    }
}
