<?php

namespace App\Modules\Core\Support;

class Numerals
{
    /** Western digits → Bengali digits (storefront copy such as ratings and counts). */
    public static function bn(int|float|string $value): string
    {
        return strtr((string) $value, ['0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪', '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯']);
    }

    /** Whole-minute "x আগে" label in Bengali for review cards. */
    public static function ago(\DateTimeInterface $at): string
    {
        $now = now();
        $days = (int) $at->diff($now)->days;

        return match (true) {
            $days < 1 => 'আজ',
            $days < 7 => self::bn($days).' দিন আগে',
            $days < 30 => self::bn((int) floor($days / 7)).' সপ্তাহ আগে',
            $days < 365 => self::bn((int) floor($days / 30)).' মাস আগে',
            default => self::bn((int) floor($days / 365)).' বছর আগে',
        };
    }
}
