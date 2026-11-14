<?php

namespace App\Modules\Cart\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'variant_id' => $this->variant_id,
            'sku' => $this->variant->sku,
            'options' => $this->variant->options,
            'qty' => $this->qty,
            'unit_price' => $this->variant->effectivePrice(),
            'price_changed' => $this->variant->effectivePrice() !== $this->unit_price_snapshot,
            'line_total' => $this->variant->effectivePrice() * $this->qty,
        ];
    }
}
