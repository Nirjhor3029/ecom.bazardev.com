<?php

namespace App\Modules\Shipping\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingRate extends Model
{
    protected $fillable = [
        'zone_id', 'method', 'min_weight', 'max_weight',
        'min_subtotal', 'max_subtotal', 'fee', 'est_days_min', 'est_days_max', 'free_over',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class, 'zone_id');
    }
}
