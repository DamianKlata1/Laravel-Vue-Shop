<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\Admin\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $this->productService->createProductFromRequest($request);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    public function update(int $productId, ProductRequest $request): RedirectResponse
    {
        $this->productService->updateProductFromRequest($productId, $request);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');

    }
    public function publish(int $productId): RedirectResponse
    {
        $this->productService->publishProduct($productId);

        return redirect()->route('admin.products.index')->with('success', 'Product published successfully');
    }
    public function delete(int $productId): RedirectResponse
    {
        $this->productService->deleteProduct($productId);

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }

    public function imageDelete(int $productImageId): RedirectResponse
    {
        $this->productService->deleteProductImage($productImageId);

        return redirect()->route('admin.products.index')->with('success', 'Product image deleted successfully');
    }
}
