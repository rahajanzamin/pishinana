<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Cart;
use App\Services\Zarinpal;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /** درگاه شبیه‌سازی‌شده — وقتی کلید زرین‌پال تنظیم نشده */
    public function fake(Order $order)
    {
        abort_unless($order->status === 'pending', 404);

        return view('payment.fake', compact('order'));
    }

    /** بازگشت از درگاه (callback) */
    public function callback(Request $request, Order $order, Zarinpal $zarinpal)
    {
        $authority = (string) $request->input('authority', '');
        $status = (string) $request->input('Status', $request->input('status', ''));

        // اگر کاربر از درگاه «انصراف» زده باشد
        if ($status !== 'OK') {
            return view('payment.result', [
                'order' => $order,
                'success' => false,
                'message' => 'پرداخت لغو شد. می‌تونی دوباره از «سفارش‌های من» اقدام کنی.',
            ]);
        }

        $result = $zarinpal->verify($order, $authority);

        if ($result['success']) {
            $order->update([
                'status' => 'paid',
                'payment_ref' => $result['ref_id'],
                'paid_at' => now(),
            ]);
        }

        return view('payment.result', [
            'order' => $order,
            'success' => $result['success'],
            'message' => $result['message'],
        ]);
    }

    /** پرداخت مجدد سفارش در انتظار */
    public function pay(Order $order, Zarinpal $zarinpal)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless($order->status === 'pending', 404);

        $callbackUrl = route('payment.callback', $order);
        return redirect($zarinpal->requestPayment($order, $callbackUrl));
    }
}
