<?php

namespace App\Modules\Shipping\Services;

use App\Exceptions\ApiException;
use App\Modules\Shipping\Models\ShippingZone;

/**
 * SRS 5.10: most-specific zone wins, falls back to the `default` zone.
 * Zone matching here is a simple keyword-in-district-name check — fine for
 * one or two zones (Dhaka vs rest of BD); a real geo_district_id match is
 * the natural next step once more zones are added.
 */
class ShippingRateResolver
{
    public function resolve(string $districtName, int $subtotal): array
    {
        $zones = ShippingZone::query()->with('rates')->orderByDesc('priority')->get();

        $zone = $zones->first(function (ShippingZone $zone) use ($districtName) {
            $keyword = $zone->match['keyword'] ?? null;

            return $keyword && str_contains(mb_strtolower($districtName), mb_strtolower($keyword));
        }) ?? $zones->first(fn (ShippingZone $zone) => $zone->match['default'] ?? false);

        if (! $zone || $zone->rates->isEmpty()) {
            throw new ApiException('AREA_NOT_SERVICEABLE', 'This area is not currently serviceable.', 422);
        }

        $rate = $zone->rates->first();

        $fee = ($rate->free_over && $subtotal >= $rate->free_over) ? 0 : $rate->fee;

        return [
            'zone_id' => $zone->id,
            'fee' => $fee,
            'est_days_min' => $rate->est_days_min,
            'est_days_max' => $rate->est_days_max,
        ];
    }
}
