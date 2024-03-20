<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Services\Admin\ProductService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(): Response
    {
        return Inertia::render('Admin/Product/index', [
            'products' => $this->productService->getProducts(),
            'brands' => Brand::all(),
            'categories' => Category::all(),
            'brandProductCounts' => Brand::getBrandProductCounts(),
            'categoryProductCounts' => Category::getCategoryProductCounts(),
        ]);
    }


    public function store(ProductRequest $request): RedirectResponse
    {
        try {
            $product = $this->productService->createProduct($request->validated());
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')->with('error', 'Product could not be created: ' . $e->getMessage());
        }

        if ($request->hasFile('product_images')) {
            try {
                $this->productService->attachProductImages($product, $request->file('product_images'));
            } catch (\Exception $e) {
                return redirect()->route('admin.products.index')->with('error', 'Product images could not be attached: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    public function update(int $productId, ProductRequest $request): RedirectResponse
    {
        try {
            $product = $this->productService->updateProduct($productId, $request->validated());
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')->with('error', 'Product could not be updated: ' . $e->getMessage());
        }

        if ($request->hasFile('product_images')) {
            try {
                $this->productService->attachProductImages($product, $request->file('product_images'));
            } catch (\Exception $e) {
                return redirect()->route('admin.products.index')->with('error', 'Product images could not be attached: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');

    }

    public function publish(int $productId): RedirectResponse
    {
        try{
            $this->productService->publishProduct($productId);
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')->with('error', 'Product could not be published: ' . $e->getMessage());
        }

        return redirect()->route('admin.products.index')->with('success', 'Product published successfully');
    }

    public function delete(int $productId): RedirectResponse
    {
        try{
            $this->productService->deleteProduct($productId);
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')->with('error', 'Product could not be deleted: ' . $e->getMessage());
        }

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }

    public function imageDelete(int $productImageId): RedirectResponse
    {
        try{
            $this->productService->deleteProductImage($productImageId);
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')->with('error', 'Product image could not be deleted: ' . $e->getMessage());
        }

        return redirect()->route('admin.products.index')->with('success', 'Product image deleted successfully');
    }
}
