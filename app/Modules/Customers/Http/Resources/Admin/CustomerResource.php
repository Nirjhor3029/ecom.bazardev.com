<?php

namespace App\Modules\Customers\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'phone_verified_at' => $this->phone_verified_at,
            'is_blocked' => $this->is_blocked,
            'risk_score' => $this->risk_score,
            'orders_count' => $this->when(isset($this->orders_count), fn () => $this->orders_count),
            'created_at' => $this->created_at,
        ];
    }
}
