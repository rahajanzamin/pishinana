@extends('layouts.admin')

@section('title', 'محصولات')

@section('content')
    <div class="admin-top">
        <h1>🛍️ محصولات</h1>
        <div style="display:flex; gap:10px">
            <form method="GET" style="display:flex; gap:8px">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="جست‌وجو..." class="input" style="width:200px">
                <button class="btn btn-soft btn-small">🔍</button>
            </form>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">➕ محصول جدید</a>
        </div>
    </div>

    <div class="panel" style="padding:0; overflow-x:auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>تصویر</th>
                    <th>نام</th>
                    <th>دسته</th>
                    <th>قیمت</th>
                    <th>موجودی</th>
                    <th>وضعیت</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td><img class="thumb" src="{{ asset($product->image ?: 'images/hero.png') }}" alt=""></td>
                        <td><b>{{ $product->name }}</b>
                            @if($product->is_new)<span class="tag-new" style="position:static">جدید</span>@endif
                        </td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->priceFormatted() }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <span class="status-badge {{ $product->is_active ? 'status-paid' : 'status-cancelled' }}">
                                {{ $product->is_active ? 'فعال' : 'غیرفعال' }}
                            </span>
                        </td>
                        <td style="white-space:nowrap">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-soft btn-small">✏️</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('حذف بشه؟')">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="dim" style="text-align:center">محصولی نیست</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $products->links() }}</div>
@endsection
