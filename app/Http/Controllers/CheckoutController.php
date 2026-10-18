<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Cart;
use App\Services\Zarinpal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /** صفحه‌ی تسویه‌حساب */
    public function show()
    {
        if (Cart::count() === 0) {
            return redirect()->route('cart.index')->with('error', 'سبد خریدت خالیه! 🧺');
        }

        $user = Auth::user();

        return view('checkout', [
            'items' => Cart::items(),
            'total' => Cart::total(),
            'user' => $user,
        ]);
    }

    /** ثبت سفارش + هدایت به درگاه */
    public function store(Request $request, Zarinpal $zarinpal)
    {
        if (Cart::count() === 0) {
            return redirect()->route('cart.index')->with('error', 'سبد خریدت خالیه!');
        }

        $data = $request->validate([
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'postal_code' => ['nullable', 'string', 'max:12'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $order = DB::transaction(function () use ($data) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'total' => Cart::total(),
                'status' => 'pending',
                'payment_method' => 'zarinpal',
                'recipient_name' => $data['recipient_name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'postal_code' => $data['postal_code'] ?? null,
                'note' => $data['note'] ?? null,
            ]);

            foreach (Cart::items() as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'color' => $item['color'] ?? null,
                ]);
            }

            // اطلاعات کاربر را هم ذخیره کن
            Auth::user()->update([
                'phone' => $data['phone'],
                'address' => $data['address'],
            ]);

            return $order;
        });

        Cart::clear();

        $callbackUrl = route('payment.callback', $order);
        return redirect($zarinpal->requestPayment($order, $callbackUrl));
    }
}
