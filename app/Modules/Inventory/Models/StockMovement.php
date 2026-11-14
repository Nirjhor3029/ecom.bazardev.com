<?php

namespace App\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    public $timestamps = false;

    protected $fillable = ['inventory_item_id', 'batch_id', 'type', 'qty', 'reference_type', 'reference_id', 'reason', 'actor_id'];

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            $m->created_at ??= now();
        });
    }
}
