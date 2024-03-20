<?php

namespace App\Services\Admin;

use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService {

    public function getProducts(): LengthAwarePaginator
    {
        return Product::filtered()->with('category', 'brand', 'product_images')->paginate(10)->withQueryString();
    }

    /**
     * @throws \Exception
     */
    public function createProduct(array $data): Product
    {
        try{
            $product = Product::create($data);
        }
        catch (\Exception $e) {
            throw new \Exception('Product could not be created: ' . $e->getMessage());
        }
        return $product;
    }

    /**
     * @throws \Exception
     */
    public function updateProduct(int $productId, array $data): Product
    {
        try{
            $product = Product::find($productId);
            $product->update($data);
        }
        catch (\Exception $e) {
            throw new \Exception('Product could not be updated: ' . $e->getMessage());
        }

        return $product;
    }

    /**
     * @throws \Exception
     */
    public function publishProduct(int $productId): void
    {
        try{
            $product = Product::find($productId);
            $product->published = !$product->published;
            $product->save();
        }
        catch (\Exception $e) {
            throw new \Exception('Product could not be published: ' . $e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteProduct(int $productId): void
    {
        try{
            Product::find($productId)->delete();
        } catch (\Exception $e) {
            throw new \Exception('Product could not be deleted: ' . $e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function attachProductImages(Product $product, array $productImages): void
    {
        foreach ($productImages as $productImage) {
            $path = $productImage->store('product_images', 'public');

            try{
                $product->product_images()->create([
                    'image' => $path
                ]);
            }
            catch (\Exception $e) {
                throw new \Exception('Product images could not be attached: ' . $e->getMessage());
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteProductImage(int $productImageId): void
    {
        try{
            ProductImage::find($productImageId)->delete();
        } catch (\Exception $e) {
            throw new \Exception('Product image could not be deleted: ' . $e->getMessage());
        }
    }
}
