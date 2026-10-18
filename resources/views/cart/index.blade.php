@extends('layouts.app')

@section('title', 'سبد خرید | پیشینانا')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>🛒 سبد خرید</h1>
            <p class="breadcrumb"><a href="{{ route('home') }}">خانه</a> / سبد خرید</p>
        </div>

        @if(count($items) > 0)
            <div class="checkout-layout">
                <div class="panel" style="padding:0; overflow:hidden">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>محصول</th>
                                <th>قیمت</th>
                                <th>تعداد</th>
                                <th>جمع</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $key => $item)
                                <tr>
                                    <td>
                                        <div class="cart-item-info">
                                            <img src="{{ asset($item['image'] ?: 'images/hero.png') }}" alt="{{ $item['name'] }}">
                                            <div>
                                                <a href="{{ route('shop.show', $item['slug']) }}"><b>{{ $item['name'] }}</b></a>
                                                @if($item['color'])
                                                    <div class="color-dots" style="margin-top:4px">
                                                        <span class="dot" style="background: {{ $item['color'] }}"></span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item['price'], 0, '', '٬') }} ت</td>
                                    <td>
                                        <div class="qty-controls">
                                            <form action="{{ route('cart.update', $key) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="qty" value="{{ $item['qty'] + 1 }}">
                                                <button type="submit">+</button>
                                            </form>
                                            <b>{{ $item['qty'] }}</b>
                                            <form action="{{ route('cart.update', $key) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="qty" value="{{ $item['qty'] - 1 }}">
                                                <button type="submit">−</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td><b>{{ number_format($item['price'] * $item['qty'], 0, '', '٬') }} ت</b></td>
                                    <td>
                                        <form action="{{ route('cart.destroy', $key) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="mini-btn" title="حذف">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="summary-card">
                    <h3>خلاصه‌ی سفارش</h3>
                    <div class="summary-row">
                        <span>تعداد اقلام</span>
                        <span>{{ \App\Services\Cart::count() }}</span>
                    </div>
                    <div class="summary-row">
                        <span>هزینه ارسال</span>
                        <span>رایگان 💕</span>
                    </div>
                    <div class="summary-row total">
                        <span>مبلغ قابل پرداخت</span>
                        <span>{{ number_format($total, 0, '', '٬') }} تومان</span>
                    </div>
                    <a href="{{ route('checkout.show') }}" class="btn btn-primary btn-block" style="margin-top:14px">
                        تسویه‌حساب نهایی ←
                    </a>
                    <a href="{{ route('shop.index') }}" class="btn btn-soft btn-block" style="margin-top:8px">ادامه‌ی خرید</a>
                </div>
            </div>
        @else
            <div class="empty-state">
                <span class="big">🧺</span>
                سبد خریدت خالیه!
                <br>
                <a href="{{ route('shop.index') }}" class="btn btn-primary" style="margin-top:14px">بریم خرید 🛍️</a>
            </div>
        @endif
    </div>
@endsection
