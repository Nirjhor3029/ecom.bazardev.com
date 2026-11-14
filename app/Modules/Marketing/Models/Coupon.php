<?php

namespace App\Modules\Marketing\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'max_discount', 'min_cart', 'starts_at', 'ends_at',
        'usage_limit', 'per_customer_limit', 'first_order_only', 'applies_to', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'first_order_only' => 'boolean',
            'applies_to' => 'json',
            'is_active' => 'boolean',
        ];
    }

    public function isCurrentlyValid(): bool
    {
        $now = now();

        return $this->is_active
            && (! $this->starts_at || $this->starts_at->lte($now))
            && (! $this->ends_at || $this->ends_at->gte($now));
    }
}
