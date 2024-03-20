<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandRequest;
use App\Models\Brand;
use App\Services\Admin\BrandService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BrandController extends Controller
{
    private BrandService $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index(): Response
    {
        $brands = $this->brandService->getBrands();

        return Inertia::render('Admin/Brands', [
            'brands' => $brands
        ]);
    }

    public function store(BrandRequest $request): RedirectResponse
    {
        try {
            $this->brandService->createBrand($request->validated());
        } catch (\Exception $e) {
            return redirect()->route('admin.brands.index')->with('error', $e->getMessage());
        }

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully');
    }

    public function update(int $brandId, BrandRequest $request): RedirectResponse
    {
        try {
            $this->brandService->updateBrand($brandId, $request->validated());
        } catch (\Exception $e) {
            return redirect()->route('admin.brands.index')->with('error', $e->getMessage());
        }

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully');
    }

    public function delete(int $brandId): RedirectResponse
    {
        try {
            $this->brandService->deleteBrand($brandId);
        } catch (\Exception $e) {
            return redirect()->route('admin.brands.index')->with('error', $e->getMessage());
        }

        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully');
    }

}
