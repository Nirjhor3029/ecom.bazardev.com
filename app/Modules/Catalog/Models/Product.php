<?php

namespace App\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'attribute_set_id', 'category_id', 'brand_id', 'name', 'slug',
        'short_desc', 'description', 'status', 'type', 'attributes',
        'tags', 'seo', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'json',
            'short_desc' => 'json',
            'description' => 'json',
            'attributes' => 'json', // non-variant attribute values, keyed by attribute code
            'tags' => 'json',
            'seo' => 'json',
            'published_at' => 'datetime',
        ];
    }

    public function attributeSet(): BelongsTo
    {
        return $this->belongsTo(AttributeSet::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProductMedia::class)->orderBy('sort');
    }

    public function defaultVariant(): ?ProductVariant
    {
        return $this->variants->firstWhere('is_default', true) ?? $this->variants->first();
    }
}
