<?php

use App\Modules\Catalog\Http\Controllers\Store\BrandController;
use App\Modules\Catalog\Http\Controllers\Store\CategoryController;
use App\Modules\Catalog\Http\Controllers\Store\ProductController;
use App\Modules\Core\Services\FeatureFlagService;
use App\Modules\Core\Services\SettingService;
use Illuminate\Support\Facades\Route;

// SRS 5.3: GET /store/bootstrap — store config, feature flags, theme tokens, terms.
Route::get('bootstrap', function (SettingService $settings, FeatureFlagService $features) {
    return response()->json([
        'data' => array_merge($settings->publicSettings()->all(), [
            'features' => $features->all()->filter()->keys(),
        ]),
    ]);
})->middleware('throttle:catalog');

Route::middleware('throttle:catalog')->group(function () {
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('brands', [BrandController::class, 'index']);
    Route::get('products/filters', [ProductController::class, 'filters']);
    Route::get('products/{slug}', [ProductController::class, 'show']);
});

Route::get('products', [ProductController::class, 'index'])->middleware('throttle:search');

// Cart/Checkout/Auth routes land here as those modules are built (Phase 1).
