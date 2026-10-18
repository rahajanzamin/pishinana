<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    /** سفارش‌های من */
    public function index()
    {
        $orders = auth()->user()->orders()->with('items')->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /** جزئیات و پیگیری سفارش */
    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }
}
