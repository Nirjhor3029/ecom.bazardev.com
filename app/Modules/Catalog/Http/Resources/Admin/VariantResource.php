<?php

namespace App\Modules\Catalog\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'options' => $this->options,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'sale_starts_at' => $this->sale_starts_at,
            'sale_ends_at' => $this->sale_ends_at,
            'weight_grams' => $this->weight_grams,
            'is_default' => $this->is_default,
            'is_active' => $this->is_active,
            'available' => $this->whenLoaded('inventoryItem', fn () => $this->inventoryItem?->available() ?? 0),
        ];
    }
}
