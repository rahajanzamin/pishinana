<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'پیشینانا | فروشگاه کوچولوی خوشگل')</title>

    {{-- فونت وزیرمتن (لوکال) --}}
    <style>
        @font-face {
            font-family: "Vazirmatn";
            src: url("{{ asset('fonts/vazirmatn-var.woff2') }}") format("woff2-variations");
            font-weight: 100 900;
            font-display: swap;
        }
        @font-face {
            font-family: "Vazirmatn";
            src: url("{{ asset('fonts/vazirmatn-latin.woff2') }}") format("woff2-variations");
            font-weight: 100 900;
            unicode-range: U+0000-00FF;
            font-display: swap;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body>

    {{-- ── نوار بالای سایت ── --}}
    <header class="site-header">
        <div class="container header-inner">
            <a href="{{ route('home') }}" class="logo">
                <span class="logo-cat">🐱</span>
                <span class="logo-text">PISHINANA</span>
                <span class="logo-tag">کوچولوی من، انتخاب تو</span>
            </a>

            <nav class="main-nav">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">خانه</a>
                <a href="{{ route('shop.index') }}" class="{{ request()->routeIs('shop.*') ? 'active' : '' }}">فروشگاه</a>
                <a href="{{ route('home') }}#collections">مجموعه‌ها</a>
                <a href="{{ route('custom.index') }}" class="{{ request()->routeIs('custom.*') ? 'active' : '' }}">طراحی اختصاصی</a>
                <a href="{{ route('home') }}#idea">ایده من</a>
            </nav>

            <div class="header-actions">
                <form action="{{ route('shop.index') }}" method="GET" class="header-search">
                    <input type="text" name="q" placeholder="جست‌وجو..." value="{{ request('q') }}">
                    <button type="submit" aria-label="جست‌وجو">🔍</button>
                </form>

                @auth
                    <a href="{{ route('profile') }}" class="icon-btn" title="پروفایل">👤</a>
                @else
                    <a href="{{ route('login') }}" class="icon-btn" title="ورود">👤</a>
                @endauth

                @auth
                    <a href="{{ route('profile') }}#favorites" class="icon-btn" title="علاقه‌مندی‌ها">💖</a>
                @endauth

                <a href="{{ route('cart.index') }}" class="icon-btn cart-btn" title="سبد خرید">
                    🛒
                    @if(\App\Services\Cart::count() > 0)
                        <span class="badge">{{ \App\Services\Cart::count() }}</span>
                    @endif
                </a>
            </div>
        </div>
    </header>

    {{-- ── پیام‌های سیستم ── --}}
    @if(session('success'))
        <div class="flash flash-success container">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error container">⚠️ {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="flash flash-error container">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ── محتوای صفحه ── --}}
    <main>
        @yield('content')
    </main>

    {{-- ── فوتر ── --}}
    <footer class="site-footer" id="about">
        <div class="container footer-inner">
            <div class="footer-brand">
                <div class="logo">
                    <span class="logo-cat">🐱</span>
                    <span class="logo-text">PISHINANA</span>
                </div>
                <p class="footer-slogan">Make it special 💕</p>
                <p class="dim">فروشگاه کوچولوی خوشگل‌های فانتزی — با عشق ساخته شده برای تو 🎀</p>
            </div>

            <div class="footer-links">
                <h4>دسترسی سریع</h4>
                <a href="{{ route('shop.index') }}">فروشگاه</a>
                <a href="{{ route('custom.index') }}">طراحی اختصاصی</a>
                <a href="{{ route('cart.index') }}">سبد خرید</a>
            </div>

            <div class="footer-links">
                <h4>حساب کاربری</h4>
                @auth
                    <a href="{{ route('profile') }}">پروفایل من</a>
                    <a href="{{ route('orders.index') }}">سفارش‌های من</a>
                    <a href="{{ route('custom.mine') }}">طراحی‌های من</a>
                @else
                    <a href="{{ route('login') }}">ورود</a>
                    <a href="{{ route('register') }}">ثبت‌نام</a>
                @endauth
            </div>

            <div class="footer-links">
                <h4>تماس با ما</h4>
                <p class="dim">ایده‌ای داری؟ از بخش «ایده من» برامون بنویس ✍️</p>
                <p class="dim">هر روز ۹ تا ۲۱ — پشتیبانی آنلاین 💬</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© ۱۴۰۵ پیشینانا — تمام حقوق محفوظ است ♡</p>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
