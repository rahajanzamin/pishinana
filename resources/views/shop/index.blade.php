@extends('layouts.app')

@section('title', 'فروشگاه | پیشینانا')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>🛍️ فروشگاه</h1>
            <p class="breadcrumb"><a href="{{ route('home') }}">خانه</a> / فروشگاه</p>
        </div>

        <div class="shop-layout">

            {{-- ── سایدبار فیلترها ── --}}
            <aside class="filters">
                <form action="{{ route('shop.index') }}" method="GET">
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <div class="filter-group">
                        <h3>دسته‌بندی‌ها</h3>
                        @foreach($categories as $category)
                            <label class="filter-check">
                                <input type="checkbox" name="category[]" value="{{ $category->id }}"
                                    @checked(in_array($category->id, (array) request('category', [])))>
                                {{ $category->icon }} {{ $category->name }}
                                <span class="dim">({{ $category->products_count }})</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="filter-group">
                        <h3>رنگ</h3>
                        <div class="color-filter">
                            @foreach(['#f8c8dc', '#fff', '#e2527f', '#c9a0dc', '#b0295a', '#f5b7cd'] as $color)
                                <button type="submit" name="color" value="{{ $color }}"
                                    style="background: {{ $color }}"
                                    class="{{ request('color') === $color ? 'active' : '' }}"
                                    title="{{ $color }}"></button>
                            @endforeach
                        </div>
                    </div>

                    <div class="filter-group">
                        <h3>محدوده قیمت (تومان)</h3>
                        <div class="range-row">
                            <input type="number" name="min" placeholder="از" value="{{ request('min') }}">
                            <input type="number" name="max" placeholder="تا" value="{{ request('max') }}">
                        </div>
                    </div>

                    <div class="filter-group">
                        <h3>مجموعه‌ها</h3>
                        @foreach($collections as $collection)
                            <label class="filter-check">
                                <input type="checkbox" name="collection[]" value="{{ $collection->id }}"
                                    @checked(in_array($collection->id, (array) request('collection', [])))>
                                {{ $collection->name }}
                                <span class="dim">({{ $collection->products_count }})</span>
                            </label>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-small">اعمال فیلترها 🔍</button>
                    <a href="{{ route('shop.index') }}" class="btn btn-soft btn-block btn-small" style="margin-top:8px">پاک کردن</a>
                </form>
            </aside>

            {{-- ── لیست محصولات ── --}}
            <section>
                <div class="shop-toolbar">
                    <form action="{{ route('shop.index') }}" method="GET" style="display:flex; gap:8px; flex-wrap:wrap; align-items:center">
                        @foreach(request()->except(['sort', 'q']) as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <input type="text" name="q" placeholder="جست‌وجوی محصول..." value="{{ request('q') }}">
                        <select name="sort" onchange="this.form.submit()">
                            <option value="">مرتب‌سازی: پیش‌فرض</option>
                            <option value="newest" @selected(request('sort') === 'newest')>جدیدترین</option>
                            <option value="cheapest" @selected(request('sort') === 'cheapest')>ارزان‌ترین</option>
                            <option value="expensive" @selected(request('sort') === 'expensive')>گران‌ترین</option>
                        </select>
                    </form>

                    <div class="view-toggle">
                        <span class="active" title="نمای شبکه‌ای">▦</span>
                        <span title="نمای لیستی">☰</span>
                    </div>
                </div>

                @if($products->count())
                    <div class="product-grid">
                        @foreach($products as $product)
                            @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>
                    <div class="pagination">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <span class="big">🥺</span>
                        محصولی با این فیلترها پیدا نشد!
                        <br>
                        <a href="{{ route('shop.index') }}" class="btn btn-soft btn-small" style="margin-top:12px">نمایش همه</a>
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection
