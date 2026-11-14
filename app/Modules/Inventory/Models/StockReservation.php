<?php

namespace App\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class StockReservation extends Model
{
    protected $fillable = ['inventory_item_id', 'order_id', 'cart_id', 'qty', 'expires_at', 'status'];

    protected function casts(): array
    {
        return ['expires_at' => 'datetime'];
    }
}
