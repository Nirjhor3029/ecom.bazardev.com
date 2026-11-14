<?php

use App\Modules\Cart\Http\Controllers\Store\CartController;
use App\Modules\Catalog\Http\Controllers\Store\BrandController;
use App\Modules\Catalog\Http\Controllers\Store\CategoryController;
use App\Modules\Catalog\Http\Controllers\Store\ProductController;
use App\Modules\Checkout\Http\Controllers\Store\CheckoutController;
use App\Modules\Core\Services\FeatureFlagService;
use App\Modules\Core\Services\SettingService;
use App\Modules\Customers\Http\Controllers\Store\AuthController;
use App\Modules\Orders\Http\Controllers\Store\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth/otp')->group(function () {
    Route::post('send', [AuthController::class, 'sendOtp'])->middleware('throttle:otp-send');
    Route::post('verify', [AuthController::class, 'verifyOtp'])->middleware('throttle:customer-login');
});

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

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'show']);
    Route::post('items', [CartController::class, 'store']);
    Route::patch('items/{item}', [CartController::class, 'update']);
    Route::delete('items/{item}', [CartController::class, 'destroy']);
    Route::post('coupon', [CartController::class, 'applyCoupon']);
    Route::delete('coupon', [CartController::class, 'removeCoupon']);
});

Route::prefix('checkout')->group(function () {
    Route::post('quote', [CheckoutController::class, 'quote']);
    Route::post('place', [CheckoutController::class, 'place'])
        ->middleware(['auth:sanctum', 'throttle:checkout']);
});

Route::get('orders/{orderNo}', [OrderController::class, 'show']);
