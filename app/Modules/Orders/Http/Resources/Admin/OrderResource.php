<?php

namespace App\Modules\Orders\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_no' => $this->order_no,
            'customer_id' => $this->customer_id,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'fulfillment_status' => $this->fulfillment_status,
            'payment_method' => $this->payment_method,
            'subtotal' => $this->subtotal,
            'discount_total' => $this->discount_total,
            'delivery_fee' => $this->delivery_fee,
            'grand_total' => $this->grand_total,
            'paid_total' => $this->paid_total,
            'due_total' => $this->due_total,
            'shipping_address' => $this->shipping_address,
            'risk_flags' => $this->risk_flags,
            'items' => $this->whenLoaded('items', fn () => $this->items->map(fn ($item) => [
                'id' => $item->id, 'name' => $item->name_snapshot, 'sku' => $item->sku,
                'qty' => $item->qty, 'unit_price' => $item->unit_price, 'line_total' => $item->line_total,
            ])),
            'status_history' => $this->whenLoaded('statusHistory', fn () => $this->statusHistory->map(fn ($h) => [
                'from' => $h->from, 'to' => $h->to, 'note' => $h->note, 'created_at' => $h->created_at,
            ])),
            'placed_at' => $this->placed_at,
        ];
    }
}
