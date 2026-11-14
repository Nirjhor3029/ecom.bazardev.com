<?php

namespace App\Modules\Checkout\Http\Controllers\Web;

use App\Exceptions\ApiException;
use App\Exceptions\PriceChangedException;
use App\Http\Controllers\Controller;
use App\Modules\Cart\Services\CartService;
use App\Modules\Cart\Support\StorefrontCart;
use App\Modules\Checkout\Actions\PlaceOrderAction;
use App\Modules\Checkout\Services\PricingService;
use App\Modules\Core\Support\PhoneNumber;
use App\Modules\Customers\Models\Customer;
use App\Modules\Customers\Services\OtpService;
use App\Modules\Customers\Services\WishlistService;
use App\Modules\Orders\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(Request $request, CartService $carts, PricingService $pricing): View
    {
        $cart = StorefrontCart::current($request, $carts)->load('items.variant.inventoryItem', 'items.variant.product.media.media');
        $customer = Auth::guard('customer')->user();

        // Returning customers get their last delivery details pre-filled.
        $last = $customer
            ? Order::query()->where('customer_id', $customer->id)->latest('placed_at')->value('shipping_address')
            : null;
        $last = is_string($last) ? json_decode($last, true) : $last;

        $district = $request->string('district')->toString() ?: ($last['district'] ?? null);
        $priced = $pricing->calculate($cart);
        $quote = null;

        if ($district) {
            try {
                $priced = $pricing->calculate($cart, $district);
                $quote = $this->quoteFrom($priced);
            } catch (ApiException $e) {
                $district = null;
            }
        }

        $divisions = DB::table('geo_divisions')->orderBy('name')->get()->map(fn ($d) => [
            'id' => $d->id, 'name' => $d->name, 'bn' => $d->bn_name ?: $d->name,
            'districts' => DB::table('geo_districts')->where('geo_division_id', $d->id)->orderBy('name')->get()
                ->map(fn ($x) => ['name' => $x->name, 'bn' => $x->bn_name ?: $x->name])->all(),
        ])->all();

        return view('storefront.checkout.show', [
            'cart' => $cart, 'priced' => $priced, 'customer' => $customer, 'last' => $last,
            'district' => $district, 'quote' => $quote, 'divisions' => $divisions,
        ]);
    }

    /** Live delivery quote for the chosen district (the form recalculates the total without a reload). */
    public function quote(Request $request, CartService $carts, PricingService $pricing): JsonResponse
    {
        $request->validate(['district' => ['required', 'string']]);
        $cart = StorefrontCart::current($request, $carts);

        try {
            $priced = $pricing->calculate($cart, $request->string('district')->toString());
        } catch (ApiException $e) {
            return response()->json(['serviceable' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json($this->quoteFrom($priced) + ['serviceable' => true]);
    }

    protected function quoteFrom(array $priced): array
    {
        return [
            'delivery_fee' => $priced['delivery_fee'],
            'grand_total' => $priced['grand_total'],
            'subtotal' => $priced['subtotal'],
            'discount_total' => $priced['discount_total'],
            'est_days_min' => $priced['delivery_estimate']['est_days_min'] ?? null,
            'est_days_max' => $priced['delivery_estimate']['est_days_max'] ?? null,
        ];
    }

    public function sendOtp(Request $request, OtpService $otp): RedirectResponse|JsonResponse
    {
        $request->validate(['phone' => ['required', 'string']]);

        $phone = PhoneNumber::normalize($request->string('phone'));

        if (! $phone) {
            $message = 'সঠিক বাংলাদেশি মোবাইল নম্বর দিন (যেমন: 01712345678)।';

            return $request->expectsJson()
                ? response()->json(['ok' => false, 'message' => $message], 422)
                : back()->withErrors(['phone' => $message]);
        }

        $otp->send($phone);
        session(['checkout_phone' => $phone]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true, 'phone' => $phone, 'ttl' => 300,
                // Local development only: SMS goes to the log driver, so surface the code for testing.
                'debug_code' => app()->environment('local') ? \Illuminate\Support\Facades\Cache::get("otp:{$phone}")['code'] ?? null : null,
            ]);
        }

        return redirect()->route('checkout.otp');
    }

    public function showOtp(): View|RedirectResponse
    {
        if (! session('checkout_phone')) {
            return redirect()->route('checkout.show');
        }

        return view('storefront.checkout.otp', ['phone' => session('checkout_phone')]);
    }

    public function verifyOtp(Request $request, OtpService $otp, WishlistService $wishlist): RedirectResponse|JsonResponse
    {
        $request->validate(['code' => ['required', 'string']]);
        $phone = session('checkout_phone');

        if (! $phone) {
            $message = 'আগে মোবাইল নম্বরে OTP পাঠান।';

            return $request->expectsJson()
                ? response()->json(['ok' => false, 'message' => $message], 422)
                : redirect()->route('checkout.show')->withErrors(['phone' => $message]);
        }

        try {
            $otp->verify($phone, $request->string('code')->toString());
        } catch (ValidationException $e) {
            return $request->expectsJson()
                ? response()->json(['ok' => false, 'message' => $e->errors()['code'][0] ?? 'OTP সঠিক নয়।'], 422)
                : back()->withErrors($e->errors());
        }

        $customer = Customer::query()->firstOrCreate(['phone' => $phone], ['phone_verified_at' => now()]);

        if (! $customer->phone_verified_at) {
            $customer->update(['phone_verified_at' => now()]);
        }

        Auth::guard('customer')->login($customer);
        $wishlist->merge($customer);
        session()->forget('checkout_phone');

        return $request->expectsJson()
            ? response()->json(['ok' => true, 'phone' => $phone])
            : redirect()->route('checkout.show');
    }

    public function place(Request $request, CartService $carts, PlaceOrderAction $action): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();

        if (! $customer) {
            return redirect()->route('checkout.show')->withErrors(['phone' => 'অর্ডার করতে আগে আপনার মোবাইল নম্বর যাচাই করুন।']);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:160'],
            'phone' => ['required', 'string'],
            'division' => ['nullable', 'string', 'max:80'],
            'district' => ['required', 'string'],
            'upazila' => ['nullable', 'string', 'max:120'],
            'area' => ['nullable', 'string', 'max:120'],
            'address_line' => ['required', 'string', 'max:500'],
            'postcode' => ['nullable', 'string', 'max:10'],
            'alt_name' => ['nullable', 'string', 'max:120'],
            'alt_phone' => ['nullable', 'string', 'max:20'],
            'note' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:cod'],
            'expected_total' => ['required', 'integer'],
        ]);

        $cart = StorefrontCart::current($request, $carts);

        // Re-used across a retried submit of the same browser form (back
        // button / double click) so the same order isn't placed twice.
        $idempotencyKey = $request->session()->get('checkout_idempotency_key') ?? Str::uuid()->toString();
        $request->session()->put('checkout_idempotency_key', $idempotencyKey);

        try {
            $order = $action->execute($cart, $customer, $data, $idempotencyKey);
        } catch (PriceChangedException $e) {
            return back()->withInput()->withErrors(['total' => 'দাম বা ডেলিভারি চার্জ বদলে গেছে — অর্ডারটি আবার যাচাই করে নিন।']);
        } catch (ApiException $e) {
            return back()->withInput()->withErrors(['order' => $e->getMessage()]);
        }

        // Remember who this is for next time (name/email only fill gaps, never overwrite).
        $customer->fill(array_filter([
            'name' => $customer->name ?: $data['name'],
            'email' => $customer->email ?: ($data['email'] ?? null),
        ]))->save();

        $request->session()->forget('checkout_idempotency_key');

        return redirect()->route('orders.success', $order->order_no);
    }
}
