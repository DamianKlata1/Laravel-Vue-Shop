<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if(is_array($this->resource)){
            return [
                'id' => $this['user_id'],
                'product' => new ProductResource(Product::with('product_images')->find($this['product_id'])),
                'quantity' => $this['quantity'],
            ];
        }else{
            return [
                'id' => $this->id,
                'product' => new ProductResource($this->product),
                'quantity' => $this->quantity,
            ];
        }


    }
}
