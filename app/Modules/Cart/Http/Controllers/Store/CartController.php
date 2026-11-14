<?php

namespace App\Modules\Cart\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Modules\Cart\Http\Requests\AddCartItemRequest;
use App\Modules\Cart\Http\Resources\CartItemResource;
use App\Modules\Cart\Models\Cart;
use App\Modules\Cart\Models\CartItem;
use App\Modules\Cart\Services\CartService;
use App\Modules\Checkout\Services\PricingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request, CartService $carts, PricingService $pricing): JsonResponse
    {
        $cart = $carts->getOrCreate($request->header('X-Cart-Token'), $request->user('sanctum'));

        return $this->respond($cart, $pricing);
    }

    public function store(AddCartItemRequest $request, CartService $carts, PricingService $pricing): JsonResponse
    {
        $cart = $carts->getOrCreate($request->header('X-Cart-Token'), $request->user('sanctum'));

        $carts->addItem($cart, $request->integer('variant_id'), $request->integer('qty'));

        return $this->respond($cart->fresh('items.variant.inventoryItem'), $pricing);
    }

    public function update(Request $request, CartItem $item, CartService $carts, PricingService $pricing): JsonResponse
    {
        $request->validate(['qty' => ['required', 'integer', 'min:0', 'max:10']]);

        $carts->updateItem($item, $request->integer('qty'));

        return $this->respond($item->cart->fresh('items.variant.inventoryItem'), $pricing);
    }

    public function destroy(CartItem $item, CartService $carts, PricingService $pricing): JsonResponse
    {
        $cart = $item->cart;
        $carts->removeItem($item);

        return $this->respond($cart->fresh('items.variant.inventoryItem'), $pricing);
    }

    public function applyCoupon(Request $request, CartService $carts, PricingService $pricing): JsonResponse
    {
        $request->validate(['code' => ['required', 'string']]);
        $cart = $carts->getOrCreate($request->header('X-Cart-Token'), $request->user('sanctum'));
        $cart->update(['coupon_code' => $request->string('code')->toString()]);

        return $this->respond($cart->fresh('items.variant.inventoryItem'), $pricing);
    }

    public function removeCoupon(Request $request, CartService $carts, PricingService $pricing): JsonResponse
    {
        $cart = $carts->getOrCreate($request->header('X-Cart-Token'), $request->user('sanctum'));
        $cart->update(['coupon_code' => null]);

        return $this->respond($cart->fresh('items.variant.inventoryItem'), $pricing);
    }

    /** CRT-4: the cart response is always server-computed pricing. */
    protected function respond(Cart $cart, PricingService $pricing): JsonResponse
    {
        $priced = $pricing->calculate($cart);

        return response()->json([
            'data' => [
                'token' => $cart->token,
                'items' => CartItemResource::collection($cart->items),
                'coupon_code' => $cart->coupon_code,
                'subtotal' => $priced['subtotal'],
                'discount_total' => $priced['discount_total'],
                'grand_total_without_delivery' => $priced['subtotal'] - $priced['discount_total'],
                'applied_rules' => $priced['applied_rules'],
            ],
        ]);
    }
}
