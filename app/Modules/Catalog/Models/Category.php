<?php

namespace App\Modules\Catalog\Models;

use App\Modules\Core\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['parent_id', 'name', 'slug', 'image_id', 'sort', 'is_active', 'seo'];

    protected function casts(): array
    {
        return [
            'name' => 'json',
            'seo' => 'json',
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function imageUrl(): ?string
    {
        return $this->image?->url();
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /** FR-CAT-1: categories nest at most 4 levels deep. */
    public function depth(): int
    {
        $depth = 1;
        $node = $this;

        while ($node->parent_id && $depth < 10) {
            $node = $node->parent;
            if (! $node) {
                break;
            }
            $depth++;
        }

        return $depth;
    }
}
