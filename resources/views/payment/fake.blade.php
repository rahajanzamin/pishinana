@extends('layouts.app')

@section('title', 'درگاه پرداخت | پیشینانا')

@section('content')
    <div class="container">
        <div class="pay-box">
            <div class="bank-icon">🏦</div>
            <h2>درگاه پرداخت امن</h2>
            <p class="dim">سفارش {{ $order->order_number }}</p>

            <div class="amount">{{ $order->priceFormatted() }}</div>

            <div class="fake-note">
                ⚠️ این یک <b>درگاه شبیه‌سازی‌شده</b> است چون هنوز کلید زرین‌پال در .env تنظیم نشده.
                با کلیک روی دکمه‌ی پایین، پرداخت آزمایشی موفق ثبت می‌شود.
            </div>

            <form action="{{ route('payment.callback', $order) }}" method="GET">
                <input type="hidden" name="authority" value="DEMO-{{ $order->id }}-{{ strtoupper(uniqid()) }}">
                <input type="hidden" name="Status" value="OK">
                <button type="submit" class="btn btn-primary btn-block">✅ پرداخت آزمایشی موفق</button>
            </form>

            <form action="{{ route('payment.callback', $order) }}" method="GET" style="margin-top:8px">
                <input type="hidden" name="authority" value="DEMO-FAIL">
                <input type="hidden" name="Status" value="NOK">
                <button type="submit" class="btn btn-soft btn-block">❌ شبیه‌سازی پرداخت ناموفق</button>
            </form>
        </div>
    </div>
@endsection
