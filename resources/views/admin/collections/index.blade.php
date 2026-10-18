@extends('layouts.admin')

@section('title', 'مجموعه‌ها')

@section('content')
    <div class="admin-top"><h1>🌸 مجموعه‌ها</h1></div>

    <div class="checkout-layout">
        <div class="panel" style="padding:0; overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr><th>تصویر</th><th>نام</th><th>محصولات</th><th>ترتیب</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($collections as $collection)
                        <tr>
                            <td><img class="thumb" src="{{ asset($collection->image) }}" alt=""></td>
                            <td><b>{{ $collection->name }}</b></td>
                            <td>{{ $collection->products_count }}</td>
                            <td>{{ $collection->sort }}</td>
                            <td>
                                <form action="{{ route('admin.collections.update', $collection) }}" method="POST" style="display:flex; gap:6px; flex-wrap:wrap">
                                    @csrf @method('PUT')
                                    <input type="text" name="name" value="{{ $collection->name }}" class="input" style="width:130px" required>
                                    <input type="number" name="sort" value="{{ $collection->sort }}" class="input" style="width:64px">
                                    <button class="btn btn-soft btn-small">ذخیره</button>
                                </form>
                                <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST" style="margin-top:6px">
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
            <h3>➕ مجموعه‌ی جدید</h3>
            <form action="{{ route('admin.collections.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="field"><label>نام</label><input type="text" name="name" required></div>
                <div class="field" style="margin-top:10px"><label>تصویر</label><input type="file" name="image" accept="image/*"></div>
                <div class="field" style="margin-top:10px"><label>توضیح</label><input type="text" name="description"></div>
                <div class="field" style="margin-top:10px"><label>ترتیب</label><input type="number" name="sort" value="0"></div>
                <button class="btn btn-primary" style="margin-top:12px">ساخت مجموعه</button>
            </form>
        </div>
    </div>
@endsection
