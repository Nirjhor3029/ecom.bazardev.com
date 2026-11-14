<?php

namespace App\Modules\Catalog\Actions\Category;

use App\Modules\Catalog\Models\Category;
use Illuminate\Validation\ValidationException;

class UpdateCategoryAction
{
    public function execute(Category $category, array $data): Category
    {
        if (array_key_exists('parent_id', $data) && $data['parent_id']) {
            $parent = Category::query()->findOrFail($data['parent_id']);

            if ($parent->depth() >= CreateCategoryAction::MAX_DEPTH) {
                throw ValidationException::withMessages([
                    'parent_id' => ['Categories can only nest '.CreateCategoryAction::MAX_DEPTH.' levels deep.'],
                ]);
            }
        }

        $category->update($data);

        return $category->refresh();
    }
}
