<?php

namespace App\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeSet extends Model
{
    protected $fillable = ['code', 'name'];

    // Named attributeDefinitions(), not attributes(): Eloquent reserves
    // the `attributes` property/accessor for the model's own column data.
    public function attributeDefinitions(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }
}
