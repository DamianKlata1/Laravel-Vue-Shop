<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    //
    public function index()
    {
        $usersAmount = User::all()->count();
        $transactionAmount = Order::all()->count();
        $orders = Order::with('orderItems.product.brand', 'orderItems.product.category', 'created_by')
            ->orderBy('id', 'desc')->paginate(5)->withQueryString();
        $totalIncome = Order::all()->sum('price');
        return Inertia::render('Admin/Dashboard', [
            'usersAmount' => $usersAmount,
            'orders' => $orders,
            'totalIncome' => $totalIncome,
            'transactionAmount' => $transactionAmount,
        ]);
    }
}
