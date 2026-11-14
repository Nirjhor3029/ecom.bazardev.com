<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 — three surfaces (SRS 3.1)
|--------------------------------------------------------------------------
| /api/v1/store/...    public + guest storefront, rate limited, no auth
| /api/v1/me/...       customer account, Sanctum customer token
| /api/v1/admin/...    staff/admin, Sanctum staff token + permission
| /api/v1/webhooks/... payment/courier inbound, provider signature auth
*/
Route::prefix('v1')->group(function () {
    Route::prefix('store')->group(base_path('routes/api/store.php'));
    Route::prefix('me')->group(base_path('routes/api/me.php'));
    Route::prefix('admin')->group(base_path('routes/api/admin.php'));
    Route::prefix('webhooks')->group(base_path('routes/api/webhooks.php'));
});
