<?php

namespace App\Modules\Catalog\Http\Resources\Store;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $this->image?->url(),
            'children' => CategoryResource::collection($this->whenLoaded('children')),
        ];
    }
}
