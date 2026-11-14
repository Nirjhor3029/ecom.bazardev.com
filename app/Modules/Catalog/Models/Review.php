<?php

namespace App\Modules\Catalog\Models;

use App\Modules\Customers\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Review extends Model
{
    protected $fillable = ['product_id', 'customer_id', 'order_item_id', 'rating', 'title', 'body', 'media', 'location', 'status', 'is_verified'];

    protected function casts(): array
    {
        return ['media' => 'json', 'rating' => 'integer', 'is_verified' => 'boolean'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /** @return list<string> public URLs of customer-attached photos (stored as public-disk paths) */
    public function photoUrls(): array
    {
        return collect($this->media ?? [])->map(fn (string $path) => Storage::disk('public')->url($path))->all();
    }
}
