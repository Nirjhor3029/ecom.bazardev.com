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
        'tags', 'seo', 'content', 'published_at',
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
            'content' => 'json', // PDP storytelling blocks (see DemoCatalogSeeder for the shape)
            'published_at' => 'datetime',
        ];
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved')->latest();
    }

    /**
     * Approved-review summary for the storefront: average, count and 1–5★ percentages.
     *
     * @return array{avg: float, count: int, dist: array<int, int>}
     */
    public function ratingSummary(): array
    {
        $counts = $this->reviews()->reorder()->selectRaw('rating, count(*) as c')->groupBy('rating')->pluck('c', 'rating');
        $total = (int) $counts->sum();
        $avg = $total ? round($counts->map(fn ($c, $r) => $c * $r)->sum() / $total, 1) : 0.0;
        $dist = [];
        foreach ([5, 4, 3, 2, 1] as $star) {
            $dist[$star] = $total ? (int) round(($counts[$star] ?? 0) / $total * 100) : 0;
        }

        return ['avg' => $avg, 'count' => $total, 'dist' => $dist];
    }

    /** Gallery photos only (videos are exposed separately via videoUrl()). */
    public function galleryImages(): \Illuminate\Support\Collection
    {
        return $this->media->filter(fn (ProductMedia $m) => $m->media && $m->media->kind() === 'image')->values();
    }

    public function videoUrl(): ?string
    {
        $video = $this->media->first(fn (ProductMedia $m) => $m->media?->kind() === 'video');

        return $video?->media?->url();
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

    /** @return array{min: int, max: int} */
    public function priceRange(): array
    {
        $active = $this->variants->where('is_active', true);
        // Show only what can actually be bought today; fall back to everything when sold out.
        $buyable = $active->filter(fn (ProductVariant $v) => ($v->inventoryItem?->available() ?? 0) > 0);
        $prices = ($buyable->isNotEmpty() ? $buyable : $active)->map(fn (ProductVariant $v) => $v->effectivePrice());

        return ['min' => $prices->min() ?? 0, 'max' => $prices->max() ?? 0];
    }

    public function primaryImageUrl(): ?string
    {
        $media = $this->media->firstWhere('is_primary', true) ?? $this->media->first();

        return $media?->media?->url();
    }
}
