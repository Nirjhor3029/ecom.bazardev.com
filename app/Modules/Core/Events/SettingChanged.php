<?php

namespace App\Modules\Core\Events;

use Illuminate\Foundation\Events\Dispatchable;

class SettingChanged
{
    use Dispatchable;

    public function __construct(
        public readonly string $key,
        public readonly mixed $value,
    ) {}
}
