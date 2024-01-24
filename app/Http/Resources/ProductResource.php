<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\BrandResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'product_images' => $this->whenLoaded('product_images', function () {
                return ProductImageResource::collection($this->product_images);
            }),
            'category_id'=>$this->category_id,
            'category'=> new CategoryResource($this->whenLoaded('category')),
            'brand_id'=>$this->brand_id,
            'brand'=> new BrandResource($this->whenLoaded('brand')),
            'in_stock'=>$this->inStock,
            'wishlisted_count'=>$this->wishlistItems()->count(),
            'reviews' => $this->whenLoaded('reviews', function () {
                return ReviewResource::collection($this->reviews);
            }),
            'rating' => $this->calculateRating(),


        ];
    }
}
