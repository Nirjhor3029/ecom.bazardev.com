<?php

namespace App\Modules\Catalog\Actions\Category;

use App\Modules\Catalog\Models\Category;
use App\Modules\Core\Support\SlugGenerator;
use Illuminate\Validation\ValidationException;

class CreateCategoryAction
{
    /** FR-CAT-1: max 4 levels deep. */
    public const MAX_DEPTH = 4;

    public function execute(array $data): Category
    {
        if (! empty($data['parent_id'])) {
            $parent = Category::query()->findOrFail($data['parent_id']);

            if ($parent->depth() >= self::MAX_DEPTH) {
                throw ValidationException::withMessages([
                    'parent_id' => ['Categories can only nest '.self::MAX_DEPTH.' levels deep.'],
                ]);
            }
        }

        $data['slug'] = $data['slug'] ?? SlugGenerator::unique('categories', $data['name']['en'] ?? $data['name']['bn']);

        return Category::query()->create($data);
    }
}
