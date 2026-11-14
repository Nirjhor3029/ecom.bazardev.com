<?php

namespace App\Modules\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    protected $fillable = [
        'order_id', 'courier', 'consignment_id', 'tracking_code', 'status',
        'cod_amount', 'cod_collected', 'fee', 'label_url', 'booked_at', 'delivered_at', 'payload',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'json',
            'booked_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ShipmentItem::class);
    }
}
