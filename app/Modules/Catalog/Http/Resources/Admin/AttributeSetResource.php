<?php

namespace App\Modules\Catalog\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeSetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'attributes' => $this->attributeDefinitions->map(fn ($a) => [
                'code' => $a->code,
                'label' => $a->label,
                'type' => $a->type,
                'options' => $a->options,
                'is_variant' => $a->is_variant,
                'is_required' => $a->is_required,
            ]),
        ];
    }
}
