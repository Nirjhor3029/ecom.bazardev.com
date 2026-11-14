<?php

namespace App\Modules\Core\Actions;

use App\Modules\Catalog\Models\Attribute;
use App\Modules\Catalog\Models\AttributeSet;
use App\Modules\Core\Models\FeatureFlag;
use App\Modules\Core\Models\Setting;
use App\Modules\Core\Services\FeatureFlagService;
use App\Modules\Core\Services\SettingService;
use App\Modules\Shipping\Models\ShippingZone;
use InvalidArgumentException;

/**
 * FR-CFG-2: apply a store preset (feature flags, settings, attribute set,
 * default shipping zones). Idempotent — re-running never overwrites a
 * feature flag whose source is already `admin`, nor a setting that already
 * has a value in the DB.
 */
class InstallStorePresetAction
{
    public function __construct(
        protected FeatureFlagService $features,
        protected SettingService $settings,
    ) {}

    public function execute(string $type): array
    {
        $preset = config("store-presets.{$type}");

        if (! $preset) {
            throw new InvalidArgumentException("Unknown store preset [{$type}]. Check config/store-presets.php.");
        }

        $this->applyFeatureFlags($preset['feature_flags'] ?? []);
        $this->applySettings($preset['settings'] ?? []);
        $this->applyTheme($preset['theme'] ?? null);
        $attributeSet = $this->applyAttributeSet($preset['attribute_set'] ?? null);
        $zones = $this->applyShippingZones($preset['shipping_zones'] ?? []);

        return [
            'type' => $type,
            'attribute_set' => $attributeSet?->code,
            'shipping_zones_created' => $zones,
        ];
    }

    protected function applyFeatureFlags(array $flags): void
    {
        foreach ($flags as $key => $enabled) {
            $existing = FeatureFlag::query()->find($key);

            if ($existing && $existing->source === 'admin') {
                continue; // admin override wins, preset never clobbers it
            }

            $this->features->set($key, (bool) $enabled, 'preset');
        }
    }

    protected function applySettings(array $settings): void
    {
        foreach ($settings as $key => $definition) {
            if (Setting::query()->find($key)) {
                continue; // already set (by a previous install or admin) — don't clobber
            }

            $this->settings->set($key, $definition['value'], $definition['group'] ?? 'general', $definition['is_public'] ?? false);
        }
    }

    protected function applyTheme(?string $theme): void
    {
        if (! $theme || Setting::query()->find('theme.active')) {
            return;
        }

        $this->settings->set('theme.active', $theme, 'theme', true);
    }

    protected function applyAttributeSet(?array $definition): ?AttributeSet
    {
        if (! $definition) {
            return null;
        }

        $set = AttributeSet::query()->updateOrCreate(
            ['code' => $definition['code']],
            ['name' => $definition['name']],
        );

        foreach ($definition['attributes'] as $attr) {
            Attribute::query()->updateOrCreate(
                ['attribute_set_id' => $set->id, 'code' => $attr['code']],
                [
                    'label' => $attr['label'] ?? [$attr['code']],
                    'type' => $attr['type'],
                    'options' => $attr['options'] ?? null,
                    'unit' => $attr['unit'] ?? null,
                    'is_variant' => $attr['is_variant'] ?? false,
                    'is_filterable' => $attr['is_filterable'] ?? false,
                    'is_required' => $attr['is_required'] ?? false,
                    'show_on_pdp' => $attr['show_on_pdp'] ?? true,
                    'sort' => $attr['sort'] ?? 0,
                ],
            );
        }

        return $set;
    }

    protected function applyShippingZones(array $zones): int
    {
        $created = 0;

        foreach ($zones as $zoneDef) {
            $zone = ShippingZone::query()->where('name', $zoneDef['name'])->first();

            if ($zone) {
                continue; // don't touch admin-customized zones/rates
            }

            $zone = ShippingZone::query()->create([
                'name' => $zoneDef['name'],
                'match' => $zoneDef['match'],
                'priority' => $zoneDef['priority'] ?? 0,
            ]);

            foreach ($zoneDef['rates'] ?? [] as $rate) {
                $zone->rates()->create($rate);
            }

            $created++;
        }

        return $created;
    }
}
