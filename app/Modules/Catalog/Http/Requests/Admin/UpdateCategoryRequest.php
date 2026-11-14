<?php

namespace App\Modules\Catalog\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('catalog.category.update');
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')->id;

        return [
            'name' => ['sometimes', 'array'],
            'name.bn' => ['required_with:name', 'string', 'max:255'],
            'name.en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', "unique:categories,slug,{$categoryId}"],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id', 'not_in:'.$categoryId],
            'image_id' => ['nullable', 'integer', 'exists:media,id'],
            'sort' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'seo' => ['nullable', 'array'],
        ];
    }
}
