<?php

namespace App\Modules\Cart\Services;

use App\Exceptions\ApiException;
use App\Modules\Cart\Models\Cart;
use App\Modules\Cart\Models\CartItem;
use App\Modules\Catalog\Models\ProductVariant;
use App\Modules\Customers\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function getOrCreate(?string $token, ?Customer $customer = null): Cart
    {
        // An authenticated customer's cart is keyed by customer_id first —
        // a logged-in client shouldn't have to keep sending X-Cart-Token.
        $cart = $customer ? Cart::query()->where('customer_id', $customer->id)->first() : null;

        $cart ??= $token ? Cart::query()->where('token', $token)->first() : null;

        if (! $cart) {
            $cart = Cart::query()->create([
                'token' => $token ?: Str::uuid()->toString(),
                'customer_id' => $customer?->id,
                'currency' => 'BDT',
                'expires_at' => now()->addDays(30),
            ]);
        }

        if ($customer && ! $cart->customer_id) {
            $cart = $this->mergeIntoCustomer($cart, $customer);
        }

        return $cart->load('items.variant.inventoryItem');
    }

    /** CRT-1: guest cart merges into the customer's existing cart on login (qty summed). */
    protected function mergeIntoCustomer(Cart $guestCart, Customer $customer): Cart
    {
        $customerCart = Cart::query()->where('customer_id', $customer->id)->where('id', '!=', $guestCart->id)->first();

        if (! $customerCart) {
            $guestCart->update(['customer_id' => $customer->id]);

            return $guestCart;
        }

        foreach ($guestCart->items as $item) {
            $existing = $customerCart->items()->where('variant_id', $item->variant_id)->first();

            if ($existing) {
                $existing->increment('qty', $item->qty);
            } else {
                $customerCart->items()->create($item->only(['variant_id', 'qty', 'unit_price_snapshot']));
            }
        }

        $guestCart->delete();

        return $customerCart;
    }

    /** CRT-2/CRT-3: validate sellable + stock policy. CRT-7: does NOT reserve. */
    public function addItem(Cart $cart, int $variantId, int $qty): CartItem
    {
        $variant = ProductVariant::query()->with(['inventoryItem', 'product'])->findOrFail($variantId);

        if (! $variant->is_active || $variant->product->status !== 'active') {
            throw ValidationException::withMessages(['variant_id' => ['This item is not available.']]);
        }

        $item = $variant->inventoryItem;

        if ($item && $item->track_stock && $item->backorder_policy === 'deny' && $item->available() < $qty) {
            throw new ApiException('STOCK_INSUFFICIENT', "Insufficient stock. Available: {$item->available()}.", 409);
        }

        $existing = $cart->items()->where('variant_id', $variantId)->first();

        if ($existing) {
            $existing->update(['qty' => $existing->qty + $qty, 'unit_price_snapshot' => $variant->effectivePrice()]);

            return $existing;
        }

        return $cart->items()->create([
            'variant_id' => $variantId,
            'qty' => $qty,
            'unit_price_snapshot' => $variant->effectivePrice(),
        ]);
    }

    public function updateItem(CartItem $item, int $qty): CartItem
    {
        if ($qty <= 0) {
            $item->delete();

            return $item;
        }

        $item->update(['qty' => $qty]);

        return $item;
    }

    public function removeItem(CartItem $item): void
    {
        $item->delete();
    }
}
