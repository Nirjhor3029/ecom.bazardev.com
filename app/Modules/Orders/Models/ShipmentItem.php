<?php

namespace App\Modules\Orders\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentItem extends Model
{
    public $timestamps = false;

    protected $fillable = ['shipment_id', 'order_item_id', 'qty'];
}
