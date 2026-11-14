<?php

namespace App\Modules\Cart\Support;

use App\Modules\Cart\Models\Cart;
use App\Modules\Cart\Services\CartService;
use App\Modules\Customers\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

/**
 * PRD §6: the storefront is Blade + the same Service/Action layer — it
 * never calls its own /api/v1/store/* routes. Cart identity here is a
 * plain HTTP cookie (not X-Cart-Token/localStorage, that's the JSON API's
 * concern) plus the `customer` session guard once OTP has been verified.
 */
class StorefrontCart
{
    public const string COOKIE = 'ecom_cart_token';

    public static function current(Request $request, CartService $carts): Cart
    {
        $token = $request->cookie(self::COOKIE);
        /** @var Customer|null $customer */
        $customer = auth('customer')->user();

        $cart = $carts->getOrCreate($token, $customer);

        if (! $request->cookie(self::COOKIE) || $request->cookie(self::COOKIE) !== $cart->token) {
            Cookie::queue(self::COOKIE, $cart->token, 60 * 24 * 30);
        }

        return $cart;
    }
}
