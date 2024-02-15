<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserHomeController extends Controller
{
    public function index(): Response
    {
        $products = Product::with('brand', 'category', 'product_images')
            ->withCount( 'wishlistItems')
            ->withAvg('reviews', 'rating')
            ->where('published', true)
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();


        return Inertia::render('User/UserHome', [
            'products' => ProductResource::collection($products),
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register')
        ]);
    }
}
