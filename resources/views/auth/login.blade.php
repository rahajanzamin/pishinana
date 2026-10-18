@extends('layouts.app')

@section('title', 'ورود | پیشینانا')

@section('content')
    <div class="container">
        <div class="auth-wrap">
            <h1>ورود 🎀</h1>
            <p class="sub">خوش برگشتی! دلمون برات تنگ شده بود 💕</p>

            <form action="{{ route('login.store') }}" method="POST">
                @csrf
                <div class="field">
                    <label>ایمیل</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="field">
                    <label>رمز عبور</label>
                    <input type="password" name="password" required>
                </div>
                <label class="filter-check">
                    <input type="checkbox" name="remember" value="1"> منو به‌خاطر بسپار
                </label>
                <button type="submit" class="btn btn-primary btn-block">ورود به حساب 💌</button>
            </form>

            <p class="auth-alt">
                حساب نداری؟ <a href="{{ route('register') }}">همین حالا ثبت‌نام کن ✨</a>
            </p>
        </div>
    </div>
@endsection
