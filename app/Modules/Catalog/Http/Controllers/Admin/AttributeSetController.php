<?php

namespace App\Modules\Catalog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Http\Resources\Admin\AttributeSetResource;
use App\Modules\Catalog\Models\AttributeSet;
use Illuminate\Http\JsonResponse;

class AttributeSetController extends Controller
{
    /** Used by the product form to render the right attribute inputs per preset (CAT-3). */
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => AttributeSetResource::collection(AttributeSet::query()->with('attributeDefinitions')->get()),
        ]);
    }
}
