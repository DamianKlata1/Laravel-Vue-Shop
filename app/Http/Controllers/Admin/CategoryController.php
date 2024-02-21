<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        $categories = Category::paginate(10)->withQueryString();
        return Inertia::render('Admin/Categories',[
            'categories' => $categories
        ]);
    }
    public function store(Request $request): RedirectResponse
    {
        $category = new Category();
        $category->name = $request->name;
        $category->save();
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }
    public function update(Request $request,$id): RedirectResponse
    {
        $category = Category::find($id);
        $category->name = $request->name;
        $category->save();
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    }
    public function delete($id): RedirectResponse
    {
        $category = Category::find($id);
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }
}
