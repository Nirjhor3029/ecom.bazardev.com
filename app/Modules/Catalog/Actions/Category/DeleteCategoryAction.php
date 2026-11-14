<?php

namespace App\Modules\Catalog\Actions\Category;

use App\Modules\Catalog\Models\Category;
use Illuminate\Validation\ValidationException;

class DeleteCategoryAction
{
    /** FR-CAT-1: can't delete a category that still has children or products. */
    public function execute(Category $category): void
    {
        if ($category->children()->exists()) {
            throw ValidationException::withMessages([
                'category' => ['This category still has subcategories. Move or delete them first.'],
            ]);
        }

        if ($category->products()->exists()) {
            throw ValidationException::withMessages([
                'category' => ['This category still has products assigned. Move them first.'],
            ]);
        }

        $category->delete();
    }
}
