<?php

namespace App\Modules\Checkout\Http\Controllers\Web;

use App\Exceptions\ApiException;
use App\Exceptions\PriceChangedException;
use App\Http\Controllers\Controller;
use App\Modules\Cart\Services\CartService;
use App\Modules\Cart\Support\StorefrontCart;
use App\Modules\Checkout\Actions\PlaceOrderAction;
use App\Modules\Checkout\Services\PricingService;
use App\Modules\Customers\Models\Customer;
use App\Modules\Customers\Services\OtpService;
use App\Modules\Customers\Services\WishlistService;
use App\Modules\Core\Support\PhoneNumber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function show(Request $request, CartService $carts, PricingService $pricing): View
    {
        $cart = StorefrontCart::current($request, $carts);
        $district = $request->string('district')->toString() ?: null;

        $priced = null;
        $error = null;

        if ($district) {
            try {
                $priced = $pricing->calculate($cart, $district);
            } catch (ApiException $e) {
                $error = $e->getMessage();
            }
        } else {
            $priced = $pricing->calculate($cart);
        }

        $customer = Auth::guard('customer')->user();

        return view('storefront.checkout.show', compact('cart', 'priced', 'district', 'error', 'customer'));
    }

    public function sendOtp(Request $request, OtpService $otp): RedirectResponse
    {
        $request->validate(['phone' => ['required', 'string']]);

        $phone = PhoneNumber::normalize($request->string('phone'));

        if (! $phone) {
            return back()->withErrors(['phone' => 'Not a valid Bangladeshi mobile number.']);
        }

        $otp->send($phone);
        session(['checkout_phone' => $phone]);

        return redirect()->route('checkout.otp');
    }

    public function showOtp(): View|RedirectResponse
    {
        if (! session('checkout_phone')) {
            return redirect()->route('checkout.show');
        }

        return view('storefront.checkout.otp', ['phone' => session('checkout_phone')]);
    }

    public function verifyOtp(Request $request, OtpService $otp, WishlistService $wishlist): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string']]);
        $phone = session('checkout_phone');

        try {
            $otp->verify($phone, $request->string('code')->toString());
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        $customer = Customer::query()->firstOrCreate(['phone' => $phone], ['phone_verified_at' => now()]);

        if (! $customer->phone_verified_at) {
            $customer->update(['phone_verified_at' => now()]);
        }

        Auth::guard('customer')->login($customer);
        $wishlist->merge($customer);
        session()->forget('checkout_phone');

        return redirect()->route('checkout.show');
    }

    public function place(Request $request, CartService $carts, PlaceOrderAction $action): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();

        if (! $customer) {
            return redirect()->route('checkout.show')->withErrors(['phone' => 'Please verify your phone number first.']);
        }

        $request->validate([
            'district' => ['required', 'string'],
            'area' => ['nullable', 'string'],
            'address_line' => ['required', 'string'],
            'postcode' => ['nullable', 'string'],
            'name' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'payment_method' => ['required', 'in:cod'],
            'expected_total' => ['required', 'integer'],
        ]);

        $cart = StorefrontCart::current($request, $carts);

        // Re-used across a retried submit of the same browser form (back
        // button / double click) so the same order isn't placed twice.
        $idempotencyKey = $request->session()->get('checkout_idempotency_key') ?? Str::uuid()->toString();
        $request->session()->put('checkout_idempotency_key', $idempotencyKey);

        try {
            $order = $action->execute($cart, $customer, $request->all(), $idempotencyKey);
        } catch (PriceChangedException $e) {
            return back()->withErrors(['total' => 'Price changed, please review your order again.']);
        } catch (ApiException $e) {
            return back()->withErrors(['order' => $e->getMessage()]);
        }

        $request->session()->forget('checkout_idempotency_key');

        return redirect()->route('orders.success', $order->order_no);
    }
}
