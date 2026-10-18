@extends('layouts.admin')

@section('title', $product->exists ? 'ویرایش محصول' : 'محصول جدید')

@section('content')
    <div class="admin-top">
        <h1>{{ $product->exists ? '✏️ ویرایش: ' . $product->name : '➕ محصول جدید' }}</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-soft">← بازگشت</a>
    </div>

    <div class="panel">
        <form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if($product->exists) @method('PUT') @endif

            <div class="form-grid">
                <div class="field">
                    <label>نام محصول *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
                </div>
                <div class="field">
                    <label>قیمت (تومان) *</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required>
                </div>
                <div class="field">
                    <label>دسته‌بندی *</label>
                    <select name="category_id" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                {{ $category->icon }} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>مجموعه</label>
                    <select name="collection_id">
                        <option value="">— بدون مجموعه —</option>
                        @foreach($collections as $collection)
                            <option value="{{ $collection->id }}" @selected(old('collection_id', $product->collection_id) == $collection->id)>
                                {{ $collection->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>موجودی *</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required>
                </div>
                <div class="field">
                    <label>ترتیب نمایش</label>
                    <input type="number" name="sort" value="{{ old('sort', $product->sort ?? 0) }}">
                </div>
                <div class="field full">
                    <label>توضیحات</label>
                    <textarea name="description">{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="field">
                    <label>تصویر اصلی (jpg/png)</label>
                    <input type="file" name="image" accept="image/*">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" style="width:90px; border-radius:12px; margin-top:8px">
                    @endif
                </div>
                <div class="field">
                    <label>رنگ‌ها (هر خط یک کد رنگ مثل #f8c8dc)</label>
                    <textarea name="colors_list" placeholder="#f8c8dc&#10;#ffffff">{{ old('colors_list', implode("\n", $product->colors ?? [])) }}</textarea>
                </div>
                <div class="field full">
                    <label>گالری تصاویر (هر خط یک آدرس تصویر — اختیاری)</label>
                    <textarea name="images_list">{{ old('images_list', implode("\n", $product->images ?? [])) }}</textarea>
                </div>
            </div>

            <div style="display:flex; gap:18px; margin-top:18px; flex-wrap:wrap">
                <label class="filter-check"><input type="checkbox" name="is_new" value="1" @checked(old('is_new', $product->is_new))> تازه/جدید</label>
                <label class="filter-check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))> فعال</label>
                <label class="filter-check"><input type="checkbox" name="is_customizable" value="1" @checked(old('is_customizable', $product->is_customizable))> قابل سفارشی‌سازی</label>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top:18px">ذخیره‌ی محصول 💾</button>
        </form>
    </div>
@endsection
