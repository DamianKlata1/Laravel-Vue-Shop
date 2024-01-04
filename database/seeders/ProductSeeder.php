<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'title' => 'Product 1',
            'price' => 100.10,
            'quantity' => 10,
            'category_id' => 1,
            'brand_id' => 1,
            'description' => 'Product 1 description'
        ]);
    }
}
