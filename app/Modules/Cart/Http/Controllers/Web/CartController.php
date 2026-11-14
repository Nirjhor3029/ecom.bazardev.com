<?php

namespace App\Modules\Cart\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Cart\Services\CartService;
use App\Modules\Cart\Support\StorefrontCart;
use App\Modules\Checkout\Services\PricingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function show(Request $request, CartService $carts, PricingService $pricing): View
    {
        $cart = StorefrontCart::current($request, $carts);
        $priced = $pricing->calculate($cart);

        return view('storefront.cart.show', compact('cart', 'priced'));
    }

    public function store(Request $request, CartService $carts): RedirectResponse
    {
        $request->validate(['variant_id' => ['required', 'integer'], 'qty' => ['required', 'integer', 'min:1', 'max:10']]);

        $cart = StorefrontCart::current($request, $carts);

        try {
            $carts->addItem($cart, $request->integer('variant_id'), $request->integer('qty'));

            // PDP "এখনই কিনুন": same add-to-cart, then straight to checkout.
            if ($request->boolean('buy_now')) {
                return redirect()->route('checkout.show');
            }

            return back()->with('success', 'Added to cart.');
        } catch (\App\Exceptions\ApiException $e) {
            return back()->withErrors(['cart' => $e->getMessage()]);
        }
    }

    public function update(Request $request, int $item, CartService $carts): RedirectResponse
    {
        $request->validate(['qty' => ['required', 'integer', 'min:0', 'max:10']]);
        $cartItem = $carts->findItemOrFail($item);
        $carts->updateItem($cartItem, $request->integer('qty'));

        return back();
    }

    public function destroy(int $item, CartService $carts): RedirectResponse
    {
        $carts->removeItem($carts->findItemOrFail($item));

        return back();
    }

    public function applyCoupon(Request $request, CartService $carts): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string']]);
        $cart = StorefrontCart::current($request, $carts);
        $cart->update(['coupon_code' => $request->string('code')->toString()]);

        return back();
    }

    public function removeCoupon(Request $request, CartService $carts): RedirectResponse
    {
        $cart = StorefrontCart::current($request, $carts);
        $cart->update(['coupon_code' => null]);

        return back();
    }
}
