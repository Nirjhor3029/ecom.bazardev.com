<?php

namespace App\Modules\Core\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SlugGenerator
{
    /**
     * FR-CAT-1/FR-CAT-2: slug auto-generate + unique. Appends -2, -3, ...
     * on collision. $ignoreId excludes the row being updated from the check.
     */
    public static function unique(string $table, string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base) ?: Str::slug(Str::random(8));
        $original = $slug;
        $i = 2;

        while (
            DB::table($table)
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }
}
