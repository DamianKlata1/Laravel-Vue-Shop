<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    private CategoryService $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function index(): Response
    {
        $categories = $this->categoryService->getCategories();

        return Inertia::render('Admin/Categories',[
            'categories' => $categories
        ]);
    }
    public function store(CategoryRequest $request): RedirectResponse
    {
        $this->categoryService->createCategoryFromRequest($request);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }
    public function update(int $categoryId, CategoryRequest $request): RedirectResponse
    {
        $this->categoryService->updateCategoryFromRequest($categoryId, $request);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    }
    public function delete(int $categoryId): RedirectResponse
    {
        $this->categoryService->deleteCategory($categoryId);

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }
}
