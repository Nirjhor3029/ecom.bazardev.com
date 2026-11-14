<?php

use App\Modules\Cart\Http\Controllers\Web\CartController;
use App\Modules\Catalog\Http\Controllers\Web\HomeController;
use App\Modules\Catalog\Http\Controllers\Web\ProductController;
use App\Modules\Checkout\Http\Controllers\Web\CheckoutController;
use App\Modules\Orders\Http\Controllers\Web\OrderController;
use Illuminate\Support\Facades\Route;

// Storefront: Blade + the same Service/Action layer as the API (PRD §6).
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart/items', [CartController::class, 'store'])->name('cart.items.store');
Route::patch('/cart/items/{item}', [CartController::class, 'update'])->name('cart.items.update');
Route::delete('/cart/items/{item}', [CartController::class, 'destroy'])->name('cart.items.destroy');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/otp/send', [CheckoutController::class, 'sendOtp'])
    ->middleware('throttle:otp-send')->name('checkout.otp.send');
Route::get('/checkout/otp', [CheckoutController::class, 'showOtp'])->name('checkout.otp');
Route::post('/checkout/otp/verify', [CheckoutController::class, 'verifyOtp'])
    ->middleware('throttle:customer-login')->name('checkout.otp.verify');
Route::post('/checkout/place', [CheckoutController::class, 'place'])
    ->middleware('throttle:checkout')->name('checkout.place');

Route::get('/orders/track', [OrderController::class, 'track'])->name('orders.track');
Route::get('/orders/{orderNo}/success', [OrderController::class, 'success'])->name('orders.success');

// Admin UI: plain Blade + Tailwind, a thin client of /api/v1/admin/* (PRD §6 Frontend Strategy).
// Auth guard is client-side (AdminApi.isLoggedIn()) since the real authorization
// boundary is the Sanctum-protected API itself, not these view routes.
Route::prefix('admin')->group(function () {
    Route::get('login', fn () => view('admin.login'))->name('admin.login');
    Route::get('/', fn () => view('admin.dashboard'))->name('admin.dashboard');

    Route::get('categories', fn () => view('admin.categories.index'));

    Route::get('products', fn () => view('admin.products.index'));
    Route::get('products/create', fn () => view('admin.products.form', ['productId' => null]));
    Route::get('products/{product}/edit', fn ($product) => view('admin.products.form', ['productId' => (int) $product]));

    Route::get('orders', fn () => view('admin.orders.index'));
    Route::get('orders/{order}', fn ($order) => view('admin.orders.show', ['orderId' => (int) $order]));
});
