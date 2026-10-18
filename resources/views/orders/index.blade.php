@extends('layouts.app')

@section('title', 'سفارش‌های من | پیشینانا')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>📦 سفارش‌های من</h1>
            <p class="breadcrumb"><a href="{{ route('home') }}">خانه</a> / سفارش‌های من</p>
        </div>

        @if($orders->count())
            <div class="panel" style="padding:0; overflow-x:auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>شماره سفارش</th>
                            <th>تاریخ</th>
                            <th>مبلغ</th>
                            <th>وضعیت</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td><b>{{ $order->order_number }}</b></td>
                                <td class="dim">{{ $order->created_at->format('Y/m/d - H:i') }}</td>
                                <td>{{ $order->priceFormatted() }}</td>
                                <td><span class="status-badge status-{{ $order->status }}">{{ $order->statusLabel() }}</span></td>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-soft btn-small">مشاهده ←</a>
                                    @if($order->status === 'pending')
                                        <a href="{{ route('payment.pay', $order) }}" class="btn btn-primary btn-small">پرداخت</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination">{{ $orders->links() }}</div>
        @else
            <div class="empty-state">
                <span class="big">📭</span>
                هنوز سفارشی ثبت نکردی!
                <br>
                <a href="{{ route('shop.index') }}" class="btn btn-primary" style="margin-top:14px">شروع خرید 🛍️</a>
            </div>
        @endif
    </div>
@endsection
