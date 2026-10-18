@extends('layouts.app')

@section('title', 'ثبت‌نام | پیشینانا')

@section('content')
    <div class="container">
        <div class="auth-wrap">
            <h1>ثبت‌نام 🌸</h1>
            <p class="sub">بیا یه حساب کوچولوی خوشگل بسازیم!</p>

            <form action="{{ route('register.store') }}" method="POST">
                @csrf
                <div class="field">
                    <label>نام تو *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus>
                </div>
                <div class="field">
                    <label>ایمیل *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="field">
                    <label>شماره تماس (اختیاری)</label>
                    <input type="text" name="phone" value="{{ old('phone') }}">
                </div>
                <div class="field">
                    <label>رمز عبور *</label>
                    <input type="password" name="password" required>
                </div>
                <div class="field">
                    <label>تکرار رمز عبور *</label>
                    <input type="password" name="password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">ساخت حساب 💖</button>
            </form>

            <p class="auth-alt">
                قبلاً ثبت‌نام کردی؟ <a href="{{ route('login') }}">ورود کن 🎀</a>
            </p>
        </div>
    </div>
@endsection
