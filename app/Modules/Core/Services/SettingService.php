<?php

namespace App\Modules\Core\Services;

use App\Modules\Core\Events\SettingChanged;
use App\Modules\Core\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * FR-CFG-1: resolution order is Redis cache -> DB `settings` -> preset/config default.
 */
class SettingService
{
    protected const string CACHE_PREFIX = 'setting:';

    protected const int CACHE_TTL = 3600;

    public function get(string $key, mixed $default = null): mixed
    {
        $value = Cache::rememberForever(self::CACHE_PREFIX.$key, function () use ($key) {
            return Setting::query()->find($key)?->value;
        });

        return $value ?? config("store-presets.defaults.{$key}", $default);
    }

    public function set(string $key, mixed $value, string $group = 'general', bool $isPublic = false): Setting
    {
        $setting = Setting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'is_public' => $isPublic],
        );

        Cache::forget(self::CACHE_PREFIX.$key);

        SettingChanged::dispatch($key, $value);

        return $setting;
    }

    public function group(string $group): Collection
    {
        return Setting::query()->where('group', $group)->get()->pluck('value', 'key');
    }

    /** Only is_public settings are safe to expose on GET /store/bootstrap. */
    public function publicSettings(): Collection
    {
        return Setting::query()->where('is_public', true)->get()->pluck('value', 'key');
    }
}
