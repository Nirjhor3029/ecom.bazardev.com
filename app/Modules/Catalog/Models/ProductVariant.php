<?php

namespace App\Modules\Catalog\Models;

use App\Modules\Inventory\Models\InventoryItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'sku', 'options', 'price', 'sale_price',
        'sale_starts_at', 'sale_ends_at', 'cost_price', 'weight_grams',
        'barcode', 'is_default', 'is_active', 'sort',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'json',
            'sale_starts_at' => 'datetime',
            'sale_ends_at' => 'datetime',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function inventoryItem(): HasOne
    {
        return $this->hasOne(InventoryItem::class, 'variant_id');
    }

    /**
     * SRS 5.6 pricing rule #1: sale_price applies only inside its window.
     * This is a display helper only — PricingService (Phase 1 checkout)
     * is the actual source of truth at order time.
     */
    public function effectivePrice(): int
    {
        if (! $this->sale_price) {
            return $this->price;
        }

        $now = now();
        $afterStart = ! $this->sale_starts_at || $this->sale_starts_at->lte($now);
        $beforeEnd = ! $this->sale_ends_at || $this->sale_ends_at->gte($now);

        return ($afterStart && $beforeEnd) ? $this->sale_price : $this->price;
    }
}
