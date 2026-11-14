<?php

namespace App\Modules\Catalog\Http\Resources\Store;

use App\Modules\Core\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $item = $this->inventoryItem;

        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'options' => $this->options,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'effective_price' => $this->effectivePrice(),
            'availability' => [
                'status' => $item?->status() ?? 'out_of_stock',
                'qty_available' => $item?->available() ?? 0,
                'max_per_order' => app(SettingService::class)->get('cart.max_per_order', 10),
            ],
        ];
    }
}
