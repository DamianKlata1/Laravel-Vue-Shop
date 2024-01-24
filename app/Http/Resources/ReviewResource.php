<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'product_id'=>$this->product_id,
            'rating'=>$this->rating,
            'comment'=>$this->comment,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'helpful_users'=>$this->helpfulUsers,
            'helpful_count'=>$this->helpfulUsers()->count(),
            'user'=>$this->whenLoaded('user'),
        ];

    }
}
