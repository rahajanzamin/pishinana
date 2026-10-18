@extends('layouts.app')

@section('title', 'تسویه‌حساب | پیشینانا')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>📦 تسویه‌حساب</h1>
            <p class="breadcrumb"><a href="{{ route('home') }}">خانه</a> / <a href="{{ route('cart.index') }}">سبد خرید</a> / تسویه‌حساب</p>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="checkout-layout">
                <div class="panel">
                    <h2>🧾 اطلاعات ارسال</h2>
                    <div class="form-grid">
                        <div class="field">
                            <label>نام و نام خانوادگی گیرنده *</label>
                            <input type="text" name="recipient_name" value="{{ old('recipient_name', $user->name) }}" required>
                        </div>
                        <div class="field">
                            <label>شماره تماس *</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required>
                        </div>
                        <div class="field full">
                            <label>نشانی کامل *</label>
                            <textarea name="address" required>{{ old('address', $user->address) }}</textarea>
                        </div>
                        <div class="field">
                            <label>کد پستی (اختیاری)</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}">
                        </div>
                        <div class="field">
                            <label>یادداشت (اختیاری)</label>
                            <input type="text" name="note" value="{{ old('note') }}" placeholder="مثلاً: کادویی بسته‌بندی بشه 🎀">
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <h3>سفارش تو</h3>
                    @foreach($items as $item)
                        <div class="summary-row">
                            <span>{{ $item['name'] }} × {{ $item['qty'] }}</span>
                            <span>{{ number_format($item['price'] * $item['qty'], 0, '', '٬') }} ت</span>
                        </div>
                    @endforeach
                    <div class="summary-row">
                        <span>ارسال</span>
                        <span>رایگان 💕</span>
                    </div>
                    <div class="summary-row total">
                        <span>مبلغ کل</span>
                        <span>{{ number_format($total, 0, '', '٬') }} تومان</span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="margin-top:14px">
                        💳 پرداخت و ثبت سفارش
                    </button>
                    <p class="dim" style="font-size:0.8rem; text-align:center; margin-top:10px">
                        با پرداخت، قوانین فروشگاه را می‌پذیری 💖
                    </p>
                </div>
            </div>
        </form>
    </div>
@endsection
