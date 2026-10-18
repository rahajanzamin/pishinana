@extends('layouts.app')

@section('title', 'طراحی اختصاصی | پیشینانا')

@push('head')
    <script type="importmap">
        { "imports": { "three": "{{ asset('vendor/three.module.js') }}" } }
    </script>
@endpush

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>🎨 طراحی اختصاصی</h1>
            <p class="breadcrumb"><a href="{{ route('home') }}">خانه</a> / طراحی اختصاصی</p>
            <p class="dim">محصول مورد نظرت رو انتخاب کن، رنگ و نوشته‌ش رو مشخص کن — پیش‌نمایش زنده‌ی سه‌بعدی همین‌جا برات می‌سازیمش! ✨</p>
        </div>

        <div class="custom-layout">
            <div class="panel">
                <h2>۱. محصول مورد نظرت رو انتخاب کن 🧸</h2>

                <form action="{{ route('custom.store') }}" method="POST" enctype="multipart/form-data" id="custom-form">
                    @csrf

                    <div class="type-grid" id="type-grid">
                        @foreach([
                            'plush' => ['🧸', 'عروسک'],
                            'bag' => ['👜', 'کیف'],
                            'stationery' => ['✏️', 'لوازم تحریر'],
                            'cosmetics' => ['💄', 'لوازم آرایشی'],
                            'mug' => ['☕', 'ماگ'],
                            'gift' => ['🎁', 'هدیه'],
                        ] as $key => [$emoji, $label])
                            <div class="type-card {{ $key === 'plush' ? 'selected' : '' }}" data-type="{{ $key }}">
                                <span class="emoji">{{ $emoji }}</span>
                                <h4>{{ $label }}</h4>
                            </div>
                        @endforeach
                        <input type="hidden" name="product_type" id="product-type" value="plush">
                    </div>

                    <h2>۲. پیش‌نمایش سه‌بعدی زنده 💫</h2>
                    <div class="scene-wrap" id="custom-scene" style="height:380px">
                        <div class="scene-hint">🖱️ بکش تا بچرخه!</div>
                    </div>

                    <h2 style="margin-top:22px">۳. رنگ و جزئیات 🎨</h2>

                    <div class="field" style="margin-bottom:14px">
                        <label>رنگ اصلی</label>
                        <div class="color-filter" id="custom-colors">
                            @foreach(['#f8c8dc' => 'صورتی', '#ffffff' => 'سفید', '#c9a0dc' => 'بنفش', '#b0295a' => 'شرابی', '#f5b7cd' => 'گلی', '#8fd6c8' => 'نعنایی'] as $hex => $name)
                                <button type="button" class="{{ $hex === '#f8c8dc' ? 'active' : '' }}" style="background: {{ $hex }}" data-color="{{ $hex }}" title="{{ $name }}"></button>
                            @endforeach
                        </div>
                        <input type="hidden" name="color" id="custom-color" value="#f8c8dc">
                    </div>

                    <div class="form-grid">
                        <div class="field">
                            <label>نوشته‌ی روی محصول (اختیاری) ✍️</label>
                            <input type="text" name="custom_text" id="custom-text" maxlength="30" placeholder="مثلاً: با عشق، سارا">
                        </div>
                        <div class="field">
                            <label>تصویر الهام‌بخش (اختیاری) 🖼️</label>
                            <input type="file" name="image" accept="image/*">
                        </div>
                        <div class="field full">
                            <label>توضیحات بیشتر</label>
                            <textarea name="description" placeholder="هر جزئیاتی که دوست داری بنویس..."></textarea>
                        </div>
                        <div class="field">
                            <label>نام تو *</label>
                            <input type="text" name="name" value="{{ auth()->user()->name ?? '' }}" required>
                        </div>
                        <div class="field">
                            <label>شماره تماس *</label>
                            <input type="text" name="phone" value="{{ auth()->user()->phone ?? '' }}" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-top:18px">
                        💌 ثبت درخواست طراحی
                    </button>
                </form>
            </div>

            {{-- سایدبار راهنما --}}
            <aside>
                <div class="sidebar-card">
                    <h3>📖 راهنمای طراحی</h3>
                    <ul>
                        <li>محصولت رو از لیست بالا انتخاب کن</li>
                        <li>رنگ دلخواهت رو بزن — پیش‌نمایش فوری عوض می‌شه</li>
                        <li>نوشته‌ی کوچولوت رو اضافه کن</li>
                        <li>درخواست رو ثبت کن تا تیم ما تماس بگیره</li>
                    </ul>
                </div>

                <div class="sidebar-card">
                    <h3>💬 پشتیبانی آنلاین</h3>
                    <ul>
                        <li>هر روز ۹ تا ۲۱</li>
                        <li>پاسخ‌گویی زیر ۲ ساعت</li>
                    </ul>
                </div>

                <div class="sidebar-card">
                    <h3>📦 چطور سفارش بدم؟</h3>
                    <ul>
                        <li>ثبت درخواست رایگان است</li>
                        <li>بعد از تأیید طرح، پرداخت انجام می‌شه</li>
                        <li>ساخت و ارسال ۵ تا ۱۰ روز کاری</li>
                    </ul>
                </div>

                <div class="sidebar-card">
                    <h3>💖 درباره ما</h3>
                    <p class="dim" style="font-size:0.88rem">
                        پیشینانا یعنی «کوچولوی من» — ما محصولاتی می‌سازیم که قلبت رو شاد کنن.
                    </p>
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module" src="{{ asset('js/customizer-scene.js') }}"></script>
@endpush
