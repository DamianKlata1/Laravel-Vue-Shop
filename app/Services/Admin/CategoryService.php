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
    public function createCategoryFromRequest(CategoryRequest $request): void
    {
        $category = new Category();
        $category->name = $request->validated()['name'];
        $category->save();
    }
    public function updateCategoryFromRequest(int $categoryId, CategoryRequest $request): void
    {
        $category = Category::find($categoryId);
        $category->name = $request->validated()['name'];
        $category->save();
    }
    public function deleteCategory(int $categoryId)
    {
        try {
            $category = Category::find($categoryId);
            $category->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')->with('error', 'Category could not be deleted: ' . $e->getMessage());
        }
    }

}
