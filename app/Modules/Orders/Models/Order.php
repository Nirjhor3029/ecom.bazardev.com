<?php

namespace App\Modules\Orders\Models;

use App\Modules\Customers\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_no', 'customer_id', 'guest_snapshot', 'status', 'payment_status', 'fulfillment_status',
        'subtotal', 'discount_total', 'delivery_fee', 'tax_total', 'grand_total', 'paid_total', 'due_total',
        'currency', 'payment_method', 'coupon_code', 'shipping_address', 'billing_address',
        'note_customer', 'note_internal', 'source', 'risk_flags', 'ip', 'user_agent', 'placed_at', 'idempotency_key',
    ];

    protected function casts(): array
    {
        return [
            'guest_snapshot' => 'json',
            'shipping_address' => 'json',
            'billing_address' => 'json',
            'risk_flags' => 'json',
            'placed_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}
