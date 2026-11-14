<?php

namespace App\Modules\Catalog\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('catalog.product.update');
    }

    public function rules(): array
    {
        return [
            'sku' => ['required', 'string', 'unique:product_variants,sku'],
            'options' => ['nullable', 'array'],
            'price' => ['required', 'integer', 'min:0'],
            'sale_price' => ['nullable', 'integer', 'min:0', 'lt:price'],
            'sale_starts_at' => ['nullable', 'date'],
            'sale_ends_at' => ['nullable', 'date', 'after:sale_starts_at'],
            'cost_price' => ['nullable', 'integer', 'min:0'],
            'weight_grams' => ['nullable', 'integer', 'min:0'],
            'barcode' => ['nullable', 'string'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort' => ['nullable', 'integer'],
        ];
    }
}
