<?php

namespace App\Modules\Core\Support;

/** SRS 5.2: normalize 01XXXXXXXXX / 8801XXXXXXXXX / +8801XXXXXXXXX -> +8801XXXXXXXXX. */
class PhoneNumber
{
    public static function normalize(string $phone): ?string
    {
        $digits = preg_replace('/\D/', '', $phone);

        if (str_starts_with($digits, '880')) {
            $digits = substr($digits, 3);
        }

        $digits = ltrim($digits, '0');

        if (! preg_match('/^1[3-9]\d{8}$/', $digits)) {
            return null; // not a valid BD mobile number (operator prefix 013-019)
        }

        return '+880'.$digits;
    }
}
