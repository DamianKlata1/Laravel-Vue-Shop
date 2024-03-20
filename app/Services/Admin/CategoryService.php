<?php

namespace App\Services\Admin;

use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{
    public function getCategories(): LengthAwarePaginator
    {
        return Category::paginate(10)->withQueryString();
    }

    /**
     * @throws \Exception
     */
    public function createCategory(array $data): void
    {
        try{
            $category = new Category();
            $category->name = $data['name'];
            $category->save();
        } catch (\Exception $e) {
            throw new \Exception('Category could not be created: ' . $e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function updateCategory(int $categoryId, array $data): void
    {
        try{
            $category = Category::find($categoryId);
            $category->name = $data['name'];
            $category->save();
        } catch (\Exception $e) {
            throw new \Exception('Category could not be updated: ' . $e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteCategory(int $categoryId): void
    {
        try {
            $category = Category::find($categoryId);
            $category->delete();
        } catch (\Exception $e) {
            throw new \Exception('Category could not be deleted: ' . $e->getMessage());
        }
    }

}
