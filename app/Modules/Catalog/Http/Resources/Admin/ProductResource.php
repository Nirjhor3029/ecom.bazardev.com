<?php

namespace App\Modules\Catalog\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'attribute_set_id' => $this->attribute_set_id,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_desc' => $this->short_desc,
            'description' => $this->description,
            'status' => $this->status,
            'type' => $this->type,
            'attributes' => $this->attributes,
            'tags' => $this->tags,
            'seo' => $this->seo,
            'variants' => VariantResource::collection($this->whenLoaded('variants')),
            'created_at' => $this->created_at,
        ];
    }
}
