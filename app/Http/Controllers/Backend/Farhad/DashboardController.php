<?php

namespace App\Http\Controllers\Backend\Farhad;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEarnings = Order::where('payment_status', 'paid')->sum('grand_total');
        $totalOrders   = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::count();

        $recentOrders  = Order::with(['customer', 'customerInfo', 'product', 'productVariation'])
                            ->latest()
                            ->take(5)
                            ->get();

        return view('backend.layouts.dashboard.index', compact(
            'totalEarnings',
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'recentOrders'
        ));
    }
}
