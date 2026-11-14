<?php

namespace App\Modules\Catalog\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Brand;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Brand::query()->orderBy('name')->get(['id', 'name', 'slug']),
        ]);
    }
}
