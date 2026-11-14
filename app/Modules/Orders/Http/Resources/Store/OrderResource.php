<?php

namespace App\Modules\Orders\Http\Resources\Store;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'order_no' => $this->order_no,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'subtotal' => $this->subtotal,
            'discount_total' => $this->discount_total,
            'delivery_fee' => $this->delivery_fee,
            'tax_total' => $this->tax_total,
            'grand_total' => $this->grand_total,
            'due_total' => $this->due_total,
            'shipping_address' => $this->shipping_address,
            'items' => $this->items->map(fn ($item) => [
                'name' => $item->name_snapshot,
                'sku' => $item->sku,
                'options' => $item->options,
                'qty' => $item->qty,
                'unit_price' => $item->unit_price,
                'line_total' => $item->line_total,
            ]),
            'placed_at' => $this->placed_at,
        ];
    }
}
