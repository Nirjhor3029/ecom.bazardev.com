<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureFlag extends Model
{
    protected $table = 'feature_flags';

    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['key', 'enabled', 'source', 'config'];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'config' => 'json',
        ];
    }
}
