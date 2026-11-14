<?php

namespace App\Modules\Catalog\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Http\Resources\Store\CategoryResource;
use App\Modules\Catalog\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $tree = Category::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort')
            ->with(['children' => fn ($q) => $q->where('is_active', true)->orderBy('sort'),
                'children.children' => fn ($q) => $q->where('is_active', true)->orderBy('sort')])
            ->get();

        return response()->json(['data' => CategoryResource::collection($tree)]);
    }
}
