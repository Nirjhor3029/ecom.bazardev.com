<?php

namespace App\Modules\Catalog\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'image_id' => $this->image_id,
            'sort' => $this->sort,
            'is_active' => $this->is_active,
            'seo' => $this->seo,
            'created_at' => $this->created_at,
        ];
    }
}
