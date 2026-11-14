<?php

namespace App\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attribute extends Model
{
    protected $fillable = [
        'attribute_set_id', 'code', 'label', 'type', 'options', 'unit',
        'is_variant', 'is_filterable', 'is_required', 'show_on_pdp', 'sort',
    ];

    protected function casts(): array
    {
        return [
            'label' => 'json',
            'options' => 'json',
            'is_variant' => 'boolean',
            'is_filterable' => 'boolean',
            'is_required' => 'boolean',
            'show_on_pdp' => 'boolean',
        ];
    }

    public function attributeSet(): BelongsTo
    {
        return $this->belongsTo(AttributeSet::class);
    }
}
