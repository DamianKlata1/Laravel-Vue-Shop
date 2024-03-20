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
        try{
            $this->categoryService->createCategory($request->validated());
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')->with('error', 'Category could not be created: ' . $e->getMessage());
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }
    public function update(int $categoryId, CategoryRequest $request): RedirectResponse
    {
        try {
            $this->categoryService->updateCategory($categoryId, $request->validated());
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')->with('error', 'Category could not be updated: ' . $e->getMessage());
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    }
    public function delete(int $categoryId): RedirectResponse
    {
        try{
            $this->categoryService->deleteCategory($categoryId);
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')->with('error', 'Category could not be deleted: ' . $e->getMessage());
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }
}
