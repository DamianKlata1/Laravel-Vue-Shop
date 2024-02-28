<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ReviewResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function showDetails(Product $product) : Response
    {
        $perPage = 10;

        $product->load(
            'category',
            'brand',
            'product_images')
            ->loadAvg('reviews', 'rating')
            ->loadCount('wishlistItems');

        $reviews = $product->reviews()
            ->with(['user', 'helpfulUsers'])
            ->withCount('helpfulUsers')
            ->paginate($perPage)
            ->withQueryString();


        return Inertia::render('User/ProductDetails', [
            'product' => new ProductResource($product),
            'reviews' => ReviewResource::collection($reviews),
        ]);
    }

    public function list(Request $request) : Response
    {
        $products = Product::where('published', true)
            ->with('category', 'brand', 'product_images')
            ->withCount('wishlistItems')
            ->withAvg('reviews', 'rating');

        $filteredProducts = $products->filtered()->paginate(10)->withQueryString();
        $categories = Category::get();
        $brands = Brand::get();
        $search = $request->search;
        return Inertia::render('User/ProductList', [
            'products' => ProductResource::collection($filteredProducts),
            'categories' => $categories,
            'brands' => $brands,
            'search' => $search,
            'brandProductCounts' => Brand::getBrandProductCounts(),
            'categoryProductCounts' => Category::getCategoryProductCounts(),
        ]);
    }
}
