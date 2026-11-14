<?php

namespace App\Modules\Core\Services;

use App\Modules\Core\Models\FeatureFlag;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * FR-CFG-4: a disabled feature must be fully absent (route/menu/API), not just hidden.
 * Callers (routes, menus, scheduler jobs) must check enabled() before registering.
 */
class FeatureFlagService
{
    protected const string CACHE_PREFIX = 'feature:';

    public function enabled(string $key, bool $default = false): bool
    {
        $value = Cache::rememberForever(self::CACHE_PREFIX.$key, function () use ($key) {
            return FeatureFlag::query()->find($key)?->enabled;
        });

        return $value ?? $default;
    }

    public function set(string $key, bool $enabled, string $source = 'admin', array $config = []): FeatureFlag
    {
        $flag = FeatureFlag::query()->updateOrCreate(
            ['key' => $key],
            ['enabled' => $enabled, 'source' => $source, 'config' => $config],
        );

        Cache::forget(self::CACHE_PREFIX.$key);

        return $flag;
    }

    public function all(): Collection
    {
        return FeatureFlag::query()->get()->pluck('enabled', 'key');
    }
}
