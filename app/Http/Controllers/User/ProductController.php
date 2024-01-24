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

class ProductController extends Controller
{
    public function showDetails(Product $product)
    {
        $perPage = 10; // Adjust the number of reviews per page as needed

        $product->load([
            'category',
            'brand',
            'product_images',
        ]);

        $reviews = $product->reviews()
            ->with('user') // Load the 'user' relation
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('User/ProductDetails', [
            'product' => new ProductResource($product),
            'reviews' => ReviewResource::collection($reviews),
        ]);
    }
    public function list(Request $request){
        $products = Product::with('category','brand','product_images')->where('published',true);
        $filteredProducts = $products->filtered()->paginate(10)->withQueryString();
        $categories = Category::get();
        $brands = Brand::get();
        $search = $request->search;
        return Inertia::render('User/ProductList',[
            'products' => ProductResource::collection($filteredProducts),
            'categories' => $categories,
            'brands' => $brands,
            'search' => $search,
            'brandProductCounts' => Product::getBrandProductCounts(),
            'categoryProductCounts' => Product::getCategoryProductCounts(),
        ]);
    }
}
