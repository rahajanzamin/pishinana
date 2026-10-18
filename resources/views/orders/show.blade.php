@extends('layouts.app')

@section('title', 'سفارش ' . $order->order_number . ' | پیشینانا')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>📦 سفارش {{ $order->order_number }}</h1>
            <p class="breadcrumb">
                <a href="{{ route('home') }}">خانه</a> /
                <a href="{{ route('orders.index') }}">سفارش‌های من</a> /
                {{ $order->order_number }}
            </p>
        </div>

        {{-- مراحل سفارش --}}
        <div class="panel">
            <h3>وضعیت سفارش: <span class="status-badge status-{{ $order->status }}">{{ $order->statusLabel() }}</span></h3>

            @php
                $steps = ['pending' => 'ثبت سفارش', 'paid' => 'پرداخت', 'processing' => 'آماده‌سازی', 'shipped' => 'ارسال', 'delivered' => 'تحویل'];
                $orderList = array_keys($steps);
                $currentIndex = array_search($order->status, $orderList);
                if ($order->status === 'cancelled') $currentIndex = 0;
            @endphp

            <div class="order-track">
                @foreach($steps as $key => $label)
                    @php $idx = array_search($key, $orderList); @endphp
                    <div class="track-step {{ $currentIndex !== false && $idx <= $currentIndex ? 'done' : '' }}">
                        <div class="ball">{{ ['✓', '💳', '🎁', '🚚', '🏠'][$idx] }}</div>
                        <span>{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="checkout-layout">
            <div class="panel" style="padding:0; overflow:hidden">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>کالا</th>
                            <th>قیمت</th>
                            <th>تعداد</th>
                            <th>جمع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <b>{{ $item->name }}</b>
                                    @if($item->color)
                                        <div class="color-dots" style="margin-top:4px">
                                            <span class="dot" style="background: {{ $item->color }}"></span>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $item->priceFormatted() }}</td>
                                <td>{{ $item->qty }}</td>
                                <td><b>{{ number_format($item->price * $item->qty, 0, '', '٬') }} ت</b></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div>
                <div class="summary-card">
                    <h3>خلاصه مالی</h3>
                    <div class="summary-row total">
                        <span>مبلغ کل</span>
                        <span>{{ $order->priceFormatted() }}</span>
                    </div>
                    @if($order->payment_ref)
                        <div class="summary-row">
                            <span>کد پیگیری پرداخت</span>
                            <span>{{ $order->payment_ref }}</span>
                        </div>
                    @endif
                    @if($order->status === 'pending')
                        <a href="{{ route('payment.pay', $order) }}" class="btn btn-primary btn-block" style="margin-top:12px">
                            💳 پرداخت این سفارش
                        </a>
                    @endif
                </div>

                <div class="summary-card" style="margin-top:14px">
                    <h3>مشخصات گیرنده</h3>
                    <p>👤 {{ $order->recipient_name }}</p>
                    <p>📞 {{ $order->phone }}</p>
                    <p>📍 {{ $order->address }}</p>
                    @if($order->postal_code)
                        <p>📫 {{ $order->postal_code }}</p>
                    @endif
                    @if($order->note)
                        <p class="dim">📝 {{ $order->note }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
