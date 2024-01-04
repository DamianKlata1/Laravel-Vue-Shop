<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::paginate(10)->withQueryString();
        return Inertia::render('Admin/Brands',[
            'brands' => $brands
        ]);
    }
    public function store(Request $request)
    {
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->save();
        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully');
    }
    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);
        $brand->name = $request->name;
        $brand->save();
        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully');
    }
    public function delete($id)
    {
        $brand = Brand::find($id);
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully');
    }

}
