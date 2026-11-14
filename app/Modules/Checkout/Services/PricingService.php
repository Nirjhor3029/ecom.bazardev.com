<?php

namespace App\Modules\Checkout\Services;

use App\Modules\Cart\Models\Cart;
use App\Modules\Marketing\Models\Coupon;
use App\Modules\Shipping\Services\ShippingRateResolver;

/**
 * SRS 5.6 / ARC-2: the one place price, discount, delivery fee and tax are
 * computed. Cart responses and order placement both call this — nothing
 * else is allowed to do its own arithmetic (business rule #1 in SRS §8).
 */
class PricingService
{
    public function __construct(protected ShippingRateResolver $shipping) {}

    public function calculate(Cart $cart, ?string $districtName = null): array
    {
        $lines = $cart->items->map(function ($item) {
            $unitPrice = $item->variant->effectivePrice();

            return [
                'variant_id' => $item->variant_id,
                'qty' => $item->qty,
                'unit_price' => $unitPrice,
                'line_total' => $unitPrice * $item->qty,
                'price_changed' => $unitPrice !== $item->unit_price_snapshot,
            ];
        });

        $subtotal = $lines->sum('line_total');

        [$discountTotal, $appliedRules] = $this->applyCoupon($cart, $subtotal);

        $delivery = $districtName
            ? $this->shipping->resolve($districtName, $subtotal)
            : ['fee' => 0, 'zone_id' => null, 'est_days_min' => null, 'est_days_max' => null];

        // TODO: no VAT rate setting exists yet (PRD open question #5) — tax
        // is currently always 0; "tax.inclusive" setting is a placeholder
        // for when a rate is configured.
        $taxTotal = 0;

        $grandTotal = max(0, $subtotal - $discountTotal + $delivery['fee'] + $taxTotal);

        return [
            'lines' => $lines,
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'delivery_fee' => $delivery['fee'],
            'delivery_estimate' => $delivery,
            'tax_total' => $taxTotal,
            'grand_total' => $grandTotal,
            'applied_rules' => $appliedRules,
        ];
    }

    /** @return array{0: int, 1: array} [discount_total, applied_rules] */
    protected function applyCoupon(Cart $cart, int $subtotal): array
    {
        if (! $cart->coupon_code) {
            return [0, []];
        }

        $coupon = Coupon::query()->where('code', $cart->coupon_code)->first();

        if (! $coupon || ! $coupon->isCurrentlyValid() || $subtotal < $coupon->min_cart) {
            return [0, []];
        }

        $discount = match ($coupon->type) {
            'percent' => (int) round($subtotal * ($coupon->value / 100)),
            'fixed' => $coupon->value,
            'free_shipping' => 0, // handled by zeroing delivery_fee at order-place time
            default => 0,
        };

        if ($coupon->max_discount) {
            $discount = min($discount, $coupon->max_discount);
        }

        // CPN-R6: discount never exceeds subtotal.
        $discount = min($discount, $subtotal);

        return [$discount, [['code' => $coupon->code, 'type' => $coupon->type, 'amount' => $discount]]];
    }
}
