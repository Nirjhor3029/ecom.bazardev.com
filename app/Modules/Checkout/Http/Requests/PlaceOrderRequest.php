<?php

namespace App\Modules\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'district' => ['required', 'string'],
            'area' => ['nullable', 'string'],
            'address_line' => ['required', 'string'],
            'postcode' => ['nullable', 'string'],
            'name' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'payment_method' => ['required', 'in:cod'], // online methods land once PAY-2 adapter is chosen
            'note' => ['nullable', 'string'],
            'expected_total' => ['required', 'integer', 'min:0'],
        ];
    }
}
