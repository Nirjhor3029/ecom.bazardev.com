<?php

namespace App\Modules\Catalog\Http\Resources\Store;

use App\Modules\Core\Support\Localized;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $activeVariants = $this->variants->where('is_active', true);
        $prices = $activeVariants->map(fn ($v) => $v->effectivePrice());
        $primaryMedia = $this->media->firstWhere('is_primary', true) ?? $this->media->first();

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => Localized::pick($request, $this->name),
            'price_range' => [
                'min' => $prices->min() ?? 0,
                'max' => $prices->max() ?? 0,
            ],
            // TODO: wire to real aggregate once the review module (MKT-5) lands.
            'rating' => ['avg' => 0, 'count' => 0],
            'image' => $primaryMedia?->media?->url(),
        ];
    }
}
