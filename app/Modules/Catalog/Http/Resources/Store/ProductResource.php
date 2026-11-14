<?php

namespace App\Modules\Catalog\Http\Resources\Store;

use App\Modules\Core\Support\Localized;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $activeVariants = $this->variants->where('is_active', true);
        $prices = $activeVariants->map(fn ($v) => $v->effectivePrice());

        $filterableAttributes = $this->attributeSet->attributeDefinitions
            ->where('is_variant', false)
            ->where('show_on_pdp', true);

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => Localized::pick($request, $this->name),
            'short_desc' => Localized::pick($request, $this->short_desc),
            'description' => Localized::pick($request, $this->description),
            'price_range' => [
                'min' => $prices->min() ?? 0,
                'max' => $prices->max() ?? 0,
            ],
            'rating' => ['avg' => 0, 'count' => 0], // TODO: MKT-5
            'attributes' => $filterableAttributes->map(fn ($attr) => [
                'code' => $attr->code,
                'label' => Localized::pick($request, $attr->label),
                'value' => $this->attributes[$attr->code] ?? null,
            ])->filter(fn ($a) => $a['value'] !== null)->values(),
            'variants' => VariantResource::collection($activeVariants),
            'media' => $this->media->map(fn ($m) => $m->media?->url())->filter()->values(),
            'seo' => $this->seo,
        ];
    }
}
