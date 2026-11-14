<?php

namespace App\Console\Commands;

use App\Modules\Core\Actions\InstallStorePresetAction;
use Illuminate\Console\Command;

/**
 * FR-CFG-2 / AC-1: `php artisan store:install --type=honey_nuts` must bring
 * a fresh DB to a running store (attribute set, feature flags, theme,
 * default shipping zones) idempotently.
 */
class StoreInstallCommand extends Command
{
    protected $signature = 'store:install {--type=}';

    protected $description = 'Apply a store preset (feature flags, settings, attribute set, shipping zones)';

    public function handle(InstallStorePresetAction $action): int
    {
        $type = $this->option('type') ?: config('store.type');

        $this->info("Installing store preset [{$type}]...");

        $result = $action->execute($type);

        $this->table(
            ['Key', 'Value'],
            [
                ['Preset type', $result['type']],
                ['Attribute set', $result['attribute_set'] ?? '-'],
                ['Shipping zones created', $result['shipping_zones_created']],
            ],
        );

        $this->info('Done. Re-run anytime — admin overrides are preserved.');

        return self::SUCCESS;
    }
}
