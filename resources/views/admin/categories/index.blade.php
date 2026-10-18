@extends('layouts.admin')

@section('title', 'دسته‌بندی‌ها')

@section('content')
    <div class="admin-top"><h1>🗂️ دسته‌بندی‌ها</h1></div>

    <div class="checkout-layout">
        <div class="panel" style="padding:0; overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr><th>تصویر</th><th>نام</th><th>محصولات</th><th>ترتیب</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td><img class="thumb" src="{{ asset($category->image) }}" alt=""></td>
                            <td><b>{{ $category->icon }} {{ $category->name }}</b></td>
                            <td>{{ $category->products_count }}</td>
                            <td>{{ $category->sort }}</td>
                            <td>
                                <form action="{{ route('admin.categories.update', $category) }}" method="POST" style="display:flex; gap:6px; flex-wrap:wrap">
                                    @csrf @method('PUT')
                                    <input type="text" name="name" value="{{ $category->name }}" class="input" style="width:120px" required>
                                    <input type="text" name="icon" value="{{ $category->icon }}" class="input" style="width:56px">
                                    <input type="number" name="sort" value="{{ $category->sort }}" class="input" style="width:64px">
                                    <button class="btn btn-soft btn-small">ذخیره</button>
                                </form>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="margin-top:6px">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-small" onclick="return confirm('حذف بشه؟')">🗑️ حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="panel">
            <h3>➕ دسته‌ی جدید</h3>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="field"><label>نام</label><input type="text" name="name" required></div>
                <div class="field" style="margin-top:10px"><label>ایموجی</label><input type="text" name="icon" placeholder="🎁"></div>
                <div class="field" style="margin-top:10px"><label>تصویر</label><input type="file" name="image" accept="image/*"></div>
                <div class="field" style="margin-top:10px"><label>ترتیب</label><input type="number" name="sort" value="0"></div>
                <button class="btn btn-primary" style="margin-top:12px">ساخت دسته</button>
            </form>
        </div>
    </div>
@endsection
