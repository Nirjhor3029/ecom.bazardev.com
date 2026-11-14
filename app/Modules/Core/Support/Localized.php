<?php

namespace App\Modules\Core\Support;

use Illuminate\Http\Request;

/**
 * API-10: storefront responses resolve i18n JSON {bn,en} down to a single
 * string based on Accept-Language. Admin responses keep the raw object
 * instead (editors need to see/edit every locale at once).
 */
class Localized
{
    public static function pick(Request $request, ?array $i18n, string $fallback = 'bn'): ?string
    {
        if (! $i18n) {
            return null;
        }

        $locale = str_starts_with((string) $request->header('Accept-Language'), 'en') ? 'en' : $fallback;

        return $i18n[$locale] ?? $i18n[$fallback] ?? (array_values($i18n)[0] ?? null);
    }
}
