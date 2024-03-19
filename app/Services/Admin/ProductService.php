<?php

namespace App\Services\Admin;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService {

    public function getProducts(): LengthAwarePaginator
    {
        return Product::filtered()->with('category', 'brand', 'product_images')->paginate(10)->withQueryString();
    }
    public function createProductFromRequest(ProductRequest $request): void
    {
        $product = Product::create($request->validated());

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $productImage) {
                $path = $productImage->store('product_images', 'public');

                $product->product_images()->create([
                    'image' => $path
                ]);
            }
        }
    }
    public function updateProductFromRequest(int $productId, ProductRequest $request): void
    {
        $product = Product::find($productId);
        $product->title = $request->title;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        if ($request->hasFile('product_images')) {
            $productImages = $request->file('product_images');
            foreach ($productImages as $productImage) {
                //$uniqueFileName = time() . '-' . uniqid() . '.' . $productImage->getClientOriginalExtension();
                $uniqueFileName = $productImage->name;
                $productImage->move('product_images', $uniqueFileName);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'product_images/' . $uniqueFileName
                ]);
            }
        }
        $product->save();
    }
    public function publishProduct(int $productId): void
    {
        $product = Product::find($productId);
        $product->published = !$product->published;
        $product->save();
    }
    public function deleteProduct(int $productId)
    {
        try{
            Product::find($productId)->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')->with('error', 'Product could not be deleted: ' . $e->getMessage());
        }
    }
    public function deleteProductImage(int $productImageId)
    {
        try{
            ProductImage::find($productImageId)->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')->with('error', 'Product image could not be deleted: ' . $e->getMessage());
        }
    }
}
