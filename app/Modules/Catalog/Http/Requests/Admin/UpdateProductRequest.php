<?php

namespace App\Modules\Catalog\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('catalog.product.update');
    }

    public function rules(): array
    {
        $productId = $this->route('product')->id;

        return [
            'attribute_set_id' => ['sometimes', 'integer', 'exists:attribute_sets,id'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],
            'name' => ['sometimes', 'array'],
            'name.bn' => ['required_with:name', 'string', 'max:255'],
            'name.en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', "unique:products,slug,{$productId}"],
            'short_desc' => ['nullable', 'array'],
            'description' => ['nullable', 'array'],
            'status' => ['sometimes', 'in:draft,active,archived'],
            'type' => ['sometimes', 'in:simple,variable,bundle'],
            'attributes' => ['nullable', 'array'],
            'tags' => ['nullable', 'array'],
            'seo' => ['nullable', 'array'],
        ];
    }
}
