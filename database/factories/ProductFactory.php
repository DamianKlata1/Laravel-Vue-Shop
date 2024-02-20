<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'quantity' => 9,
            'description' => $this->faker->text(),
            'published' => true,
            'inStock' => true,
            'price' => $this->faker->randomFloat(2),
            'created_by' => 1,
            'updated_by' => 1,
            'deleted_at' => Carbon::now(),
            'deleted_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'brand_id' => Brand::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
