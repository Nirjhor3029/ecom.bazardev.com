<?php

namespace App\Modules\Inventory\Models;

use App\Modules\Catalog\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    protected $fillable = ['variant_id', 'on_hand', 'reserved', 'low_stock_threshold', 'track_stock', 'backorder_policy'];

    protected function casts(): array
    {
        return ['track_stock' => 'boolean'];
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function batches(): HasMany
    {
        return $this->hasMany(InventoryBatch::class);
    }

    public function available(): int
    {
        return max(0, $this->on_hand - $this->reserved);
    }

    /** SRS 5.3: availability.status in_stock|low_stock|out_of_stock|preorder */
    public function status(): string
    {
        if (! $this->track_stock) {
            return 'in_stock';
        }

        $available = $this->available();

        if ($available > 0) {
            return $available <= $this->low_stock_threshold ? 'low_stock' : 'in_stock';
        }

        return $this->backorder_policy === 'preorder' ? 'preorder' : 'out_of_stock';
    }
}
