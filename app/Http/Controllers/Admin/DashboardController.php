<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\CustomDesign;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'productCount' => Product::count(),
            'orderCount' => Order::count(),
            'userCount' => User::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'revenue' => Order::where('status', 'paid')->sum('total'),
            'newDesigns' => CustomDesign::where('status', 'pending')->count(),
            'unreadMessages' => ContactMessage::where('is_read', false)->count(),
            'latestOrders' => Order::with('user')->latest()->take(6)->get(),
        ]);
    }
}
