<?php

namespace App\Modules\Catalog\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('catalog.product.create');
    }

    public function rules(): array
    {
        return [
            'attribute_set_id' => ['required', 'integer', 'exists:attribute_sets,id'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],
            'name' => ['required', 'array'],
            'name.bn' => ['required', 'string', 'max:255'],
            'name.en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'short_desc' => ['nullable', 'array'],
            'description' => ['nullable', 'array'],
            'status' => ['nullable', 'in:draft,active,archived'],
            'type' => ['required', 'in:simple,variable,bundle'],
            'attributes' => ['nullable', 'array'],
            'tags' => ['nullable', 'array'],
            'seo' => ['nullable', 'array'],

            'variants' => ['nullable', 'array'],
            'variants.*.sku' => ['required', 'string', 'distinct', 'unique:product_variants,sku'],
            'variants.*.options' => ['nullable', 'array'],
            'variants.*.price' => ['required', 'integer', 'min:0'],
            'variants.*.sale_price' => ['nullable', 'integer', 'min:0'],
            'variants.*.weight_grams' => ['nullable', 'integer', 'min:0'],
            'variants.*.is_default' => ['nullable', 'boolean'],
        ];
    }
}
