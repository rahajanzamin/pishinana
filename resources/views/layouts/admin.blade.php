<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'پنل مدیریت') | پیشینانا</title>
    <style>
        @font-face {
            font-family: "Vazirmatn";
            src: url("{{ asset('fonts/vazirmatn-var.woff2') }}") format("woff2-variations");
            font-weight: 100 900;
            font-display: swap;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-side">
            <div class="brand">🐱 PISHINANA | مدیریت</div>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 داشبورد</a>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">🛍️ محصولات</a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">🗂️ دسته‌بندی‌ها</a>
            <a href="{{ route('admin.collections.index') }}" class="{{ request()->routeIs('admin.collections.*') ? 'active' : '' }}">🌸 مجموعه‌ها</a>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">📦 سفارش‌ها</a>
            <a href="{{ route('admin.designs.index') }}" class="{{ request()->routeIs('admin.designs.*') ? 'active' : '' }}">🎨 طراحی‌های سفارشی</a>
            <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">💌 پیام‌ها</a>
            <a href="{{ route('home') }}">🏠 مشاهده‌ی سایت</a>
            <form action="{{ route('logout') }}" method="POST" style="margin-top:14px">
                @csrf
                <button type="submit" class="btn btn-soft btn-block btn-small">خروج 👋</button>
            </form>
        </aside>

        <main class="admin-main">
            @if(session('success'))
                <div class="flash flash-success">✅ {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="flash flash-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
