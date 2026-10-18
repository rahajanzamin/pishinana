@extends('layouts.app')

@section('title', 'پیشینانا | کوچولوی من، انتخاب تو')

@push('head')
    <script type="importmap">
        { "imports": { "three": "{{ asset('vendor/three.module.js') }}" } }
    </script>
@endpush

@section('content')

    {{-- ── هیرو: صحنه‌ی سه‌بعدی + متن ── --}}
    <section class="container">
        <div class="hero">
            <div class="hero-text">
                <span class="hero-eyebrow">🎀 به فروشگاه کوچولوی خوشگل‌ها خوش اومدی</span>
                <h1>دنبال کوچولوی <span>خودت</span> رو بساز 🧸</h1>
                <p class="sub">
                    محصولات فانتزی، دست‌ساز و اختصاصی با طرح دلخواه تو —
                    از عروسک و کیف تا ماگ و لوازم تحریر، همه با عشق 💕
                </p>
                <div class="hero-actions">
                    <a href="{{ route('custom.index') }}" class="btn btn-soft">✨ شخصی‌سازی محصول</a>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary">🛍️ مشاهده محصولات</a>
                </div>
            </div>

            <div class="hero-visual">
                {{-- صحنه‌ی سه‌بعدی تعاملی (با موس بچرخونش!) --}}
                <div class="scene-wrap" id="hero-scene">
                    <div class="scene-hint">🖱️ بکش تا بچرخه!</div>
                </div>
                <div class="hero-sticker s1">💕 ارسال رایگان</div>
                <div class="hero-sticker s2">⭐ ۷ روز ضمانت</div>
            </div>

            <span class="hero-heart" style="top:14%; left:10%">💗</span>
            <span class="hero-heart" style="top:60%; left:4%; animation-delay:1s">✨</span>
            <span class="hero-heart" style="top:22%; left:46%; animation-delay:2s">🌸</span>
        </div>
    </section>

    {{-- ── دسته‌بندی‌ها ── --}}
    <section class="section container">
        <div class="section-head">
            <h2><span class="bow">🎀</span> دسته‌بندی‌ها</h2>
            <p class="sub">هر چیزی که قلبت بخواد، اینجا پیدا می‌شه</p>
        </div>
        <div class="cat-grid">
            @foreach($categories as $category)
                <a href="{{ route('shop.index', ['category' => [$category->id]]) }}" class="cat-card">
                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}">
                    <h3>{{ $category->icon }} {{ $category->name }}</h3>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ── طراحی اختصاصی ── --}}
    <section class="section container">
        <div class="custom-cta">
            <img src="{{ asset('images/custom-banner.png') }}" alt="طراحی اختصاصی">
            <div>
                <h2>✨ محصول خودت رو طراحی کن!</h2>
                <p class="dim">
                    رنگ، طرح و جزئیات مورد علاقه‌ت رو انتخاب کن،
                    ما اون رو با دست‌سازی دقیق برات می‌سازیم.
                </p>
                <ul class="steps">
                    <li><span class="num">۱</span> انتخاب محصول</li>
                    <li><span class="num">۲</span> انتخاب طرح و رنگ</li>
                    <li><span class="num">۳</span> اضافه کردن نوشته</li>
                    <li><span class="num">۴</span> تأیید و ثبت سفارش</li>
                </ul>
                <a href="{{ route('custom.index') }}" class="btn btn-primary">🎨 شروع طراحی</a>
            </div>
        </div>
    </section>

    {{-- ── مجموعه‌های ویژه ── --}}
    <section class="section container" id="collections">
        <div class="section-head">
            <h2>🌸 مجموعه‌های ویژه 🌸</h2>
            <p class="sub">هر مجموعه، یه حس خوب</p>
        </div>
        <div class="col-grid">
            @foreach($collections as $collection)
                <a href="{{ route('shop.index', ['collection' => [$collection->id]]) }}" class="col-card">
                    <img src="{{ asset($collection->image) }}" alt="{{ $collection->name }}">
                    <div class="col-body">
                        <h3>🦋 {{ $collection->name }}</h3>
                        <p class="dim">{{ $collection->description }}</p>
                        <span class="more">مشاهده ←</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ── تازه‌ترین‌ها ── --}}
    @if($newProducts->count())
        <section class="section container">
            <div class="section-head">
                <h2>💖 تازه‌ترین‌ها 💖</h2>
                <p class="sub">جدیدترین کوچولوها رسیدن!</p>
            </div>
            <div class="product-grid grid-4">
                @foreach($newProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            <div style="text-align:center; margin-top:26px">
                <a href="{{ route('shop.index') }}" class="btn btn-soft">مشاهده‌ی همه‌ی محصولات 🛍️</a>
            </div>
        </section>
    @endif

    {{-- ── ایده‌ی تو ── --}}
    <section class="section container" id="idea">
        <div class="idea-box">
            <div>
                <h2>💡 ایده‌ای تو ذهن داری؟</h2>
                <p class="dim">
                    شاید دقیقاً همون چیزی باشه که بهش نیاز داریم!
                    ایده‌ت رو برامون بنویس، ما بهش گوش می‌دیم. 🌷
                </p>
                <form class="idea-form" action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="field">
                        <input type="text" name="name" placeholder="نام تو 💗" required>
                    </div>
                    <div class="field">
                        <input type="email" name="email" placeholder="ایمیل (اختیاری)">
                    </div>
                    <div class="field">
                        <textarea name="message" placeholder="ایده‌ات رو بنویس..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">💌 ارسال ایده</button>
                </form>
            </div>
            <div class="idea-deco">
                <span class="big">💡</span>
                Dream ✧ Create<br>PISHINANA 💕
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script type="module" src="{{ asset('js/hero-scene.js') }}"></script>
@endpush
