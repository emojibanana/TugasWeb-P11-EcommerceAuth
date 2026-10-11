<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

// Khusus admin (route dilindungi middleware ['auth','admin']).
class DashboardController extends Controller
{
    public function index()
    {
        // Eager loading demo (Bonus): with('category') cegah N+1.
        // Tanpa eager loading: 1 + N query. Dengan: 2 query saja.
        $stats = [
            'users' => User::count(),
            'products' => Product::count(),
            'categories' => Category::count(),
            'orders' => Order::count(),
            'revenue' => Order::where('status', 'delivered')->sum('total'),
        ];

        $lowStock = Product::with('category')
            ->where('stock', '<', 10)
            ->orderBy('stock')
            ->limit(5)
            ->get();

        $latestOrders = Order::with(['user', 'items.product'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'lowStock', 'latestOrders'));
    }
}
