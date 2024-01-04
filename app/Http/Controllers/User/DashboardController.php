<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderItems.product.brand', 'orderItems.product.category')
            ->where('created_by', auth()->user()->id)->orderBy('id', 'desc')->get();
        return Inertia::render('User/Dashboard', [
            'orders' => $orders
        ]);
    }
}
