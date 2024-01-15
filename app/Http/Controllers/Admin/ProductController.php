<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::filtered()->with('category', 'brand', 'product_images')->paginate(10)->withQueryString();
        $brands = Brand::all();
        $categories = Category::all();

        return Inertia::render('Admin/Product/index', [
            'products' => $products,
            'brands' => $brands,
            'categories' => $categories,
            'brandProductCounts' => Product::getBrandProductCounts(),
            'categoryProductCounts' => Product::getCategoryProductCounts(),
        ]);
    }


    public function store(Request $request)
    {
        $product = new Product();
        $product->title = $request->title;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $product->save();


        if ($request->hasFile('product_images')) {
            $productImages = $request->file('product_images');
            foreach ($productImages as $productImage) {
                $uniqueFileName = time() . '-' . uniqid() . '.' . $productImage->getClientOriginalExtension();
                $productImage->move('product_images', $uniqueFileName);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'product_images/' . $uniqueFileName
                ]);
            }
        }
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    public function update($id, Request $request)
    {
        $product = Product::find($id);
        $product->title = $request->title;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        if ($request->hasFile('product_images')) {
            $productImages = $request->file('product_images');
            foreach ($productImages as $productImage) {
                $uniqueFileName = time() . '-' . uniqid() . '.' . $productImage->getClientOriginalExtension();
                $productImage->move('product_images', $uniqueFileName);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'product_images/' . $uniqueFileName
                ]);
            }
        }


        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');

    }
    public function publish($id)
    {
        $product = Product::find($id);
        $product->published = !$product->published;
        $product->save();
        return redirect()->route('admin.products.index')->with('success', 'Product published successfully');
    }
    public function delete($id)
    {
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }

    public function imageDelete($id)
    {
        $productImage = ProductImage::find($id);
        $productImage->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product image deleted successfully');
    }
}
