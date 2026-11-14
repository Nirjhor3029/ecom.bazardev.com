<?php

use Illuminate\Support\Facades\Route;

// SRS 5.3: GET /store/bootstrap — store config, feature flags, theme tokens, terms.
// Catalog/Cart/Checkout/Auth routes land here as those modules are built (Phase 1).

Route::get('bootstrap', function () {
    return response()->json(['data' => ['message' => 'storefront bootstrap placeholder']]);
})->middleware('throttle:catalog');
