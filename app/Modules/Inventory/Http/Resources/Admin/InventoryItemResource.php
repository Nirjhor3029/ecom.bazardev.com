<?php

namespace App\Modules\Inventory\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'variant_id' => $this->variant_id,
            'sku' => $this->variant->sku,
            'options' => $this->variant->options,
            'product_name' => $this->variant->product->name,
            'product_id' => $this->variant->product_id,
            'on_hand' => $this->on_hand,
            'reserved' => $this->reserved,
            'available' => $this->available(),
            'low_stock_threshold' => $this->low_stock_threshold,
            'status' => $this->status(),
            'track_stock' => $this->track_stock,
            'backorder_policy' => $this->backorder_policy,
        ];
    }
}
