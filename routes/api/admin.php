<?php

use App\Modules\Catalog\Http\Controllers\Admin\AttributeSetController;
use App\Modules\Catalog\Http\Controllers\Admin\BrandController;
use App\Modules\Catalog\Http\Controllers\Admin\CategoryController;
use App\Modules\Catalog\Http\Controllers\Admin\ProductController;
use App\Modules\Core\Http\Controllers\Admin\AuthController;
use App\Modules\Core\Http\Controllers\Admin\SettingsController;
use App\Modules\Customers\Http\Controllers\Admin\CustomerController;
use App\Modules\Inventory\Http\Controllers\Admin\InventoryController;
use App\Modules\Orders\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:admin-login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// Named "admin.*" throughout: apiResource's auto-generated names (products.index,
// etc.) would otherwise collide with the storefront's identically-named Blade
// routes in the same global name table (web.php and api.php share one namespace).
Route::middleware('auth:sanctum')->name('admin.')->group(function () {
    Route::get('attribute-sets', [AttributeSetController::class, 'index']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('brands', BrandController::class);
    Route::apiResource('products', ProductController::class);
    Route::post('products/{product}/variants', [ProductController::class, 'storeVariant']);
    Route::patch('products/{product}/variants/{variant}', [ProductController::class, 'updateVariant']);
    Route::delete('products/{product}/variants/{variant}', [ProductController::class, 'destroyVariant']);

    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::post('orders/{order}/transition', [OrderController::class, 'transition']);
    Route::post('orders/{order}/shipments', [OrderController::class, 'bookShipment']);

    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customers/{customer}', [CustomerController::class, 'show']);
    Route::post('customers/{customer}/block', [CustomerController::class, 'block']);
    Route::post('customers/{customer}/unblock', [CustomerController::class, 'unblock']);

    Route::get('inventory', [InventoryController::class, 'index']);
    Route::post('inventory/{item}/adjust', [InventoryController::class, 'adjust']);
    Route::get('inventory/{item}/movements', [InventoryController::class, 'movements']);

    Route::get('settings', [SettingsController::class, 'index']);
    Route::patch('settings/{key}', [SettingsController::class, 'updateSetting']);
    Route::patch('features/{key}', [SettingsController::class, 'updateFeature']);
});
