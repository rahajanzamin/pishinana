@extends('layouts.app')

@section('title', 'پروفایل من | پیشینانا')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>👤 پروفایل من</h1>
            <p class="breadcrumb"><a href="{{ route('home') }}">خانه</a> / پروفایل</p>
        </div>

        <div class="checkout-layout">
            <div class="panel">
                <h2>اطلاعات من</h2>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="form-grid">
                        <div class="field">
                            <label>نام</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="field">
                            <label>شماره تماس</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                        </div>
                        <div class="field full">
                            <label>نشانی</label>
                            <textarea name="address">{{ old('address', $user->address) }}</textarea>
                        </div>
                        <div class="field">
                            <label>رمز عبور جدید (اختیاری)</label>
                            <input type="password" name="password">
                        </div>
                        <div class="field">
                            <label>تکرار رمز جدید</label>
                            <input type="password" name="password_confirmation">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top:16px">ذخیره‌ی تغییرات ✨</button>
                </form>

                <div style="margin-top:26px; display:flex; gap:10px; flex-wrap:wrap">
                    <a href="{{ route('orders.index') }}" class="btn btn-soft">📦 سفارش‌های من</a>
                    <a href="{{ route('custom.mine') }}" class="btn btn-soft">🎨 طراحی‌های من</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-soft">خروج از حساب 👋</button>
                    </form>
                </div>
            </div>

            <div class="summary-card" id="favorites">
                <h3>💖 علاقه‌مندی‌ها</h3>
                @if($favorites->count())
                    @foreach($favorites as $product)
                        <div class="summary-row" style="align-items:center">
                            <div class="cart-item-info">
                                <img src="{{ asset($product->image ?: 'images/hero.png') }}" alt="{{ $product->name }}" style="width:46px;height:46px;border-radius:10px;object-fit:cover">
                                <a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a>
                            </div>
                            <form action="{{ route('favorites.toggle', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="mini-btn">💔</button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <p class="dim">هنوز چیزی به علاقه‌مندی‌ها اضافه نکردی 🥺</p>
                @endif
            </div>
        </div>
    </div>
@endsection
