<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

// Otorisasi order via OrderPolicy: user hanya miliknya,
// admin semua, editor lihat semua.
class OrderController extends Controller
{
    public function index(): View
    {
        $user = request()->user();

        $orders = Order::with(['items.product'])
            ->when($user->role === 'user', fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        $order->load(['user', 'items.product']);

        return view('orders.show', compact('order'));
    }
}
