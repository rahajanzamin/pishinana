{{-- کارت محصول — توی فروشگاه و صفحه‌ی اصلی استفاده می‌شود --}}
<div class="product-card">
    <a href="{{ route('shop.show', $product->slug) }}" class="product-image">
        @if($product->is_new)
            <span class="tag-new">جدید</span>
        @endif
        @if($product->stock <= 0)
            <span class="tag-out">ناموجود</span>
        @endif
        <img src="{{ asset($product->image ?: 'images/hero.png') }}" alt="{{ $product->name }}">
    </a>

    <div class="product-body">
        <h3><a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a></h3>

        @if($product->colors)
            <div class="color-dots">
                @foreach($product->colors as $color)
                    <span class="dot" style="background: {{ $color }}"></span>
                @endforeach
            </div>
        @endif

        <div class="product-foot">
            <span class="price">{{ $product->priceFormatted() }}</span>
            <div class="product-actions">
                @auth
                    <form action="{{ route('favorites.toggle', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="mini-btn" title="علاقه‌مندی">
                            {{ auth()->user()->favorites->contains($product->id) ? '💖' : '🤍' }}
                        </button>
                    </form>
                @endauth
                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    <button type="submit" class="mini-btn mini-btn-cart" title="افزودن به سبد">🛒</button>
                </form>
            </div>
        </div>
    </div>
</div>
