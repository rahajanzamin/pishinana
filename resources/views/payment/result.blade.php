@extends('layouts.app')

@section('title', 'نتیجه‌ی پرداخت | پیشینانا')

@section('content')
    <div class="container">
        <div class="pay-box">
            <div class="result-icon">{{ $success ? '🎉' : '😞' }}</div>
            <h2>{{ $success ? 'پرداخت موفق!' : 'پرداخت ناموفق' }}</h2>
            <p class="dim">{{ $message }}</p>

            <div class="amount">{{ $order->priceFormatted() }}</div>

            @if($success)
                <p class="dim">
                    کد پیگیری: <b>{{ $order->payment_ref }}</b><br>
                    شماره سفارش: <b>{{ $order->order_number }}</b>
                </p>
                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary" style="margin-top:16px">
                    📦 پیگیری سفارش
                </a>
            @else
                <a href="{{ route('orders.index') }}" class="btn btn-primary" style="margin-top:16px">
                    🔄 تلاش مجدد از «سفارش‌های من»
                </a>
            @endif

            <a href="{{ route('home') }}" class="btn btn-soft" style="margin-top:10px">بازگشت به خانه 🏠</a>
        </div>
    </div>
@endsection
