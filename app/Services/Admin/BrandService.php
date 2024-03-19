<?php

namespace App\Services\Admin;

use App\Http\Requests\Admin\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class BrandService
{
public function getBrands(): LengthAwarePaginator
    {
        return Brand::paginate(10)->withQueryString();
    }
    public function createBrandFromRequest(BrandRequest $request): void
    {
        $brand = new Brand();
        $brand->name = $request->validated()['name'];
        $brand->save();
    }
    public function updateBrandFromRequest(int $brandId, BrandRequest $request): void
    {
        $brand = Brand::find($brandId);
        $brand->name = $request->validated()['name'];
        $brand->save();
    }
    public function deleteBrand(int $brandId)
    {
        try {
            $brand = Brand::find($brandId);
            $brand->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.brands.index')->with('error', 'Brand could not be deleted: ' . $e->getMessage());
        }
    }

}
