<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@wp.pl',
            'password' => Hash::make('password'),
            'isAdmin' => true
        ]);
        Brand::create([
            'name' => 'Adidas',
            'slug' => 'adidas',
        ]);
        Brand::create([
            'name' => 'Nike',
            'slug' => 'nike',
        ]);
        Brand::create([
            'name' => 'Kipsta',
            'slug' => 'kipsta',
        ]);
        Category::create([
            'name' => 'Dress',
            'slug' => 'dress',
        ]);
        Category::create([
            'name' => 'Ball',
            'slug' => 'ball',
        ]);
        Category::create([
            'name' => 'Shoes',
            'slug' => 'shoes',
        ]);
        Product::create([
            'title' => 'Product 1',
            'price' => 100.10,
            'quantity' => 10,
            'published' => true,
            'category_id' => 1,
            'brand_id' => 1,
            'description' => 'Product 1 description'
        ]);
    }
}
