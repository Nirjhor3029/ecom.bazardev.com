<?php

namespace App\Modules\Catalog\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('catalog.category.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'array'],
            'name.bn' => ['required', 'string', 'max:255'],
            'name.en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'image_id' => ['nullable', 'integer', 'exists:media,id'],
            'sort' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'seo' => ['nullable', 'array'],
        ];
    }
}
