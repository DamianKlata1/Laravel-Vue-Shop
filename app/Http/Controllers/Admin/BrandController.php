<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BrandController extends Controller
{
    public function index(): Response
    {
        $brands = Brand::paginate(10)->withQueryString();
        return Inertia::render('Admin/Brands',[
            'brands' => $brands
        ]);
    }
    public function store(Request $request): RedirectResponse
    {
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->save();
        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully');
    }
    public function update(Request $request, $id): RedirectResponse
    {
        $brand = Brand::find($id);
        $brand->name = $request->name;
        $brand->save();
        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully');
    }
    public function delete($id): RedirectResponse
    {
        $brand = Brand::find($id);
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully');
    }

}
