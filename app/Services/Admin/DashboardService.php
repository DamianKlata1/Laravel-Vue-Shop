<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\User;
use App\Models\Visitor;

class DashboardService
{
    public function getDataForDashboard()
    {
        $usersAmount = User::count();
        $transactionAmount = Order::count();
        $orders = Order::with('orderItems.product.brand', 'orderItems.product.category', 'created_by')
            ->orderBy('id', 'desc');
        $filteredOrders = $orders->filtered()->paginate(5)->withQueryString();
        $totalIncome = Order::where('status', '=', 'paid')->sum('price');
        $uniqueVisitorsAmount = Visitor::count();

        return [
            'usersAmount' => $usersAmount,
            'orders' => $filteredOrders,
            'totalIncome' => $totalIncome,
            'transactionAmount' => $transactionAmount,
            'uniqueVisitorsAmount' => $uniqueVisitorsAmount,
        ];
    }

}
