<?php

namespace App\Modules\Core\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url(),
            'name' => $this->original_name,
            'mime' => $this->mime,
            'kind' => $this->kind(),
            'size' => $this->size,
            'width' => $this->width,
            'height' => $this->height,
            'alt' => $this->alt,
            'created_at' => $this->created_at,
        ];
    }
}
