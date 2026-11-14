<?php

namespace App\Modules\Cart\Http\Controllers\Web;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Modules\Cart\Models\CartItem;
use App\Modules\Cart\Services\CartService;
use App\Modules\Cart\Support\StorefrontCart;
use App\Modules\Catalog\Services\ProductCatalogService;
use App\Modules\Checkout\Services\PricingService;
use App\Modules\Customers\Services\WishlistService;
use App\Modules\Marketing\Models\Coupon;
use App\Modules\Shipping\Models\ShippingZone;
use App\Modules\Shipping\Services\ShippingRateResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /** Cart-page labels for the seeded zones (falls back to the zone name). */
    private const array ZONE_LABELS = ['Dhaka Metro' => 'ঢাকা মহানগর', 'Outside Dhaka' => 'ঢাকার বাইরে (সারা দেশ)'];

    public function show(Request $request, CartService $carts, PricingService $pricing, ShippingRateResolver $shipping, ProductCatalogService $catalog): View
    {
        $cart = StorefrontCart::current($request, $carts);
        $priced = $pricing->calculate($cart);
        $payable = $priced['subtotal'] - $priced['discount_total'];

        // Delivery estimate for the area picked on this page (defaults to the highest-priority zone).
        $zones = ShippingZone::query()->with('rates')->orderByDesc('priority')->get()->filter(fn ($z) => $z->rates->isNotEmpty())->values();
        $zone = $zones->firstWhere('id', (int) $request->session()->get('cart_zone')) ?? $zones->first();
        $delivery = $zone ? $shipping->estimateForZone($zone, $payable) : null;
        $zoneOptions = $zones->map(fn (ShippingZone $z) => [
            'id' => $z->id,
            'label' => self::ZONE_LABELS[$z->name] ?? $z->name,
            'fee' => $shipping->estimateForZone($z, $payable)['fee'],
        ]);

        $coupon = $cart->coupon_code ? Coupon::query()->where('code', $cart->coupon_code)->first() : null;

        $inCart = $cart->items->map(fn ($i) => $i->variant->product_id)->all();
        $suggested = $catalog->search(Request::create('/', 'GET', ['sort' => 'popular', 'in_stock' => 1]))
            ->whereNotIn('products.id', $inCart)->limit(4)->get();

        return view('storefront.cart.show', [
            'cart' => $cart, 'priced' => $priced, 'delivery' => $delivery, 'zone' => $zone, 'zoneOptions' => $zoneOptions,
            'coupon' => $coupon, 'couponApplied' => $priced['discount_total'] > 0,
            'grandTotal' => max(0, $payable + ($delivery['fee'] ?? 0)),
            'suggested' => $suggested,
            'undo' => $request->session()->get('cart_undo'),
        ]);
    }

    public function store(Request $request, CartService $carts): RedirectResponse
    {
        $request->validate(['variant_id' => ['required', 'integer'], 'qty' => ['required', 'integer', 'min:1', 'max:10']]);

        $cart = StorefrontCart::current($request, $carts);

        try {
            $carts->addItem($cart, $request->integer('variant_id'), $request->integer('qty'));
            $request->session()->forget('cart_undo');

            // PDP "এখনই কিনুন": same add-to-cart, then straight to checkout.
            if ($request->boolean('buy_now')) {
                return redirect()->route('checkout.show');
            }

            return back()->with('success', 'Added to cart.');
        } catch (ApiException $e) {
            return back()->withErrors(['cart' => $e->getMessage()]);
        }
    }

    /** Cart lines are addressed by id, so make sure the line belongs to *this* visitor's cart. */
    private function ownItem(Request $request, CartService $carts, int $item): CartItem
    {
        $cartItem = $carts->findItemOrFail($item);
        abort_unless($cartItem->cart_id === StorefrontCart::current($request, $carts)->id, 404);

        return $cartItem;
    }

    public function update(Request $request, int $item, CartService $carts): RedirectResponse
    {
        $request->validate(['qty' => ['required', 'integer', 'min:0', 'max:10']]);
        $cartItem = $this->ownItem($request, $carts, $item);
        $carts->updateItem($cartItem, $request->integer('qty'));

        return back();
    }

    public function destroy(Request $request, int $item, CartService $carts): RedirectResponse
    {
        $cartItem = $this->ownItem($request, $carts, $item);
        $variant = $cartItem->variant->loadMissing('product');

        // Remember what was removed so the cart page can offer a one-click undo.
        $request->session()->flash('cart_undo', [
            'variant_id' => $cartItem->variant_id,
            'qty' => $cartItem->qty,
            'name' => ($variant->product->name['bn'] ?? '').($variant->weight_grams ? ' ('.$variant->weight_grams.' গ্রাম)' : ''),
        ]);
        $carts->removeItem($cartItem);

        return back();
    }

    public function clear(Request $request, CartService $carts): RedirectResponse
    {
        StorefrontCart::current($request, $carts)->items()->delete();

        return back();
    }

    /** "পরে কিনুন": move the line to the wishlist (never toggles it off). */
    public function saveForLater(Request $request, int $item, CartService $carts, WishlistService $wishlist): RedirectResponse
    {
        $cartItem = $this->ownItem($request, $carts, $item);
        $productId = $cartItem->variant->product_id;
        $customer = auth('customer')->user();

        if (! $wishlist->has($customer, $productId)) {
            $wishlist->toggle($customer, $productId);
        }
        $carts->removeItem($cartItem);

        return back()->with('success', 'পণ্যটি উইশলিস্টে সরানো হয়েছে।');
    }

    public function applyCoupon(Request $request, CartService $carts, PricingService $pricing): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string']]);
        $cart = StorefrontCart::current($request, $carts);
        $code = strtoupper(trim($request->string('code')->toString()));
        $coupon = Coupon::query()->where('code', $code)->first();
        $subtotal = $pricing->calculate($cart)['subtotal'];

        $error = match (true) {
            ! $coupon || ! $coupon->is_active => 'কুপন কোডটি সঠিক নয়।',
            ! $coupon->isCurrentlyValid() => 'এই কুপনের মেয়াদ শেষ হয়ে গেছে।',
            $subtotal < $coupon->min_cart => 'এই কুপনের জন্য নূন্যতম অর্ডার ৳'.number_format($coupon->min_cart / 100, 0).'।',
            default => null,
        };

        if ($error) {
            return back()->withErrors(['coupon' => $error])->withInput();
        }

        $cart->update(['coupon_code' => $coupon->code]);

        return back();
    }

    public function removeCoupon(Request $request, CartService $carts): RedirectResponse
    {
        StorefrontCart::current($request, $carts)->update(['coupon_code' => null]);

        return back();
    }

    public function area(Request $request): RedirectResponse
    {
        $request->validate(['zone_id' => ['required', 'integer', 'exists:shipping_zones,id']]);
        $request->session()->put('cart_zone', $request->integer('zone_id'));

        return back();
    }
}
