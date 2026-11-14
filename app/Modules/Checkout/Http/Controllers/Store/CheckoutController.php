<?php

namespace App\Modules\Checkout\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Modules\Cart\Services\CartService;
use App\Modules\Checkout\Actions\PlaceOrderAction;
use App\Modules\Checkout\Http\Requests\PlaceOrderRequest;
use App\Modules\Checkout\Services\PricingService;
use App\Modules\Orders\Http\Resources\Store\OrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    /** CHK flow step 1 — stateless, not cacheable: pricing + delivery estimate for the current cart. */
    public function quote(Request $request, CartService $carts, PricingService $pricing): JsonResponse
    {
        $request->validate(['district' => ['required', 'string']]);

        $cart = $carts->getOrCreate($request->header('X-Cart-Token'), $request->user('sanctum'));
        $priced = $pricing->calculate($cart, $request->string('district')->toString());

        return response()->json(['data' => [
            'subtotal' => $priced['subtotal'],
            'discount_total' => $priced['discount_total'],
            'delivery_fee' => $priced['delivery_fee'],
            'delivery_estimate' => $priced['delivery_estimate'],
            'tax_total' => $priced['tax_total'],
            'grand_total' => $priced['grand_total'],
            'payment_methods' => ['cod'], // online methods land once PAY-2 adapter is chosen
        ]]);
    }

    /** CHK flow step 2 — CHK-R3/R4: atomic + idempotent order placement. */
    public function place(PlaceOrderRequest $request, CartService $carts, PlaceOrderAction $action): JsonResponse
    {
        $idempotencyKey = $request->header('Idempotency-Key');

        if (! $idempotencyKey) {
            throw ValidationException::withMessages(['idempotency_key' => ['The Idempotency-Key header is required.']]);
        }

        $customer = $request->user(); // auth:sanctum customer guard — OTP verify already happened (CHK-R1)
        $cart = $carts->getOrCreate($request->header('X-Cart-Token'), $customer);

        $order = $action->execute($cart, $customer, $request->validated(), $idempotencyKey);

        return response()->json(['data' => new OrderResource($order)], 201);
    }
}
