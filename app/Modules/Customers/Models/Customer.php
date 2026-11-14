<?php

namespace App\Modules\Customers\Models;

use App\Modules\Orders\Models\Order;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, SoftDeletes;

    protected $fillable = ['name', 'phone', 'email', 'password', 'phone_verified_at', 'is_blocked', 'risk_score'];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'phone_verified_at' => 'datetime',
            'is_blocked' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
