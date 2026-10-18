@extends('layouts.app')

@section('title', $product->name . ' | پیشینانا')

@section('content')
    <div class="container">
        <p class="breadcrumb" style="padding-top:22px">
            <a href="{{ route('home') }}">خانه</a> /
            <a href="{{ route('shop.index') }}">فروشگاه</a> /
            <a href="{{ route('shop.index', ['category' => [$product->category_id]]) }}">{{ $product->category->name }}</a> /
            {{ $product->name }}
        </p>

        <div class="product-detail">
            <div class="detail-image">
                <img src="{{ asset($product->image ?: 'images/hero.png') }}" alt="{{ $product->name }}">
            </div>

            <div class="detail-info">
                <h1>{{ $product->name }}</h1>
                <p class="dim">{{ $product->category->name }} @if($product->collection) • {{ $product->collection->name }} @endif</p>

                <p class="price" style="margin:12px 0">{{ $product->priceFormatted() }}</p>

                @if($product->stock > 0)
                    <p class="stock-ok">✅ موجود در انبار ({{ $product->stock }} عدد)</p>
                @else
                    <p class="stock-no">❌ ناموجود</p>
                @endif

                <p class="desc">{{ $product->description }}</p>

                @if($product->colors)
                    <div class="detail-colors">
                        <h4>رنگ مورد علاقه‌ت رو انتخاب کن:</h4>
                        <div class="color-dots" id="color-picker">
                            @foreach($product->colors as $i => $color)
                                <span class="dot {{ $i === 0 ? 'selected' : '' }}" style="background: {{ $color }}" data-color="{{ $color }}"></span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    <input type="hidden" name="color" id="selected-color" value="{{ $product->colors[0] ?? '' }}">

                    <div class="qty-row">
                        <label>تعداد:</label>
                        <input type="number" name="qty" value="1" min="1" max="20" class="input">
                    </div>

                    @if($product->stock > 0)
                        <button type="submit" class="btn btn-primary">🛒 افزودن به سبد خرید</button>
                    @endif
                </form>

                <div class="detail-actions">
                    @auth
                        <form action="{{ route('favorites.toggle', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-soft">
                                {{ auth()->user()->favorites->contains($product->id) ? '💖 در علاقه‌مندی‌ها' : '🤍 افزودن به علاقه‌مندی‌ها' }}
                            </button>
                        </form>
                    @endauth
                    @if($product->is_customizable)
                        <a href="{{ route('custom.index') }}" class="btn btn-soft">✨ سفارشی‌سازی</a>
                    @endif
                </div>
            </div>
        </div>

        @if($related->count())
            <section class="section">
                <div class="section-head">
                    <h2>🌸 محصولات مشابه</h2>
                </div>
                <div class="product-grid grid-4">
                    @foreach($related as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        // انتخاب رنگ روی صفحه‌ی محصول
        document.querySelectorAll('#color-picker .dot').forEach(dot => {
            dot.addEventListener('click', () => {
                document.querySelectorAll('#color-picker .dot').forEach(d => d.classList.remove('selected'));
                dot.classList.add('selected');
                document.getElementById('selected-color').value = dot.dataset.color || '';
            });
        });
    </script>
@endpush
