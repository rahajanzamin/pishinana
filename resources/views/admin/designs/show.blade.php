@extends('layouts.admin')

@section('title', 'بررسی طراحی')

@section('content')
    <div class="admin-top">
        <h1>🎨 بررسی درخواست طراحی #{{ $customDesign->id }}</h1>
        <a href="{{ route('admin.designs.index') }}" class="btn btn-soft">← بازگشت</a>
    </div>

    <div class="checkout-layout">
        <div class="panel">
            <h3>جزئیات درخواست</h3>
            <p>👤 {{ $customDesign->name ?: ($customDesign->user->name ?? '—') }} — 📞 {{ $customDesign->phone }}</p>
            <p>🧸 محصول: <b>{{ $customDesign->typeLabel() }}</b></p>
            <p>✍️ نوشته: {{ $customDesign->custom_text ?: '—' }}</p>
            <p>
                🎨 رنگ:
                @if($customDesign->color)
                    <span style="display:inline-block;width:20px;height:20px;border-radius:50%;background:{{ $customDesign->color }};vertical-align:middle"></span>
                    {{ $customDesign->color }}
                @else — @endif
            </p>
            <p class="dim">📝 {{ $customDesign->description ?: 'بدون توضیحات' }}</p>

            @if($customDesign->image_path)
                <img src="{{ asset('storage/' . $customDesign->image_path) }}" style="max-width:280px; border-radius:16px; margin-top:12px">
            @endif
        </div>

        <div class="summary-card">
            <h3>مدیریت وضعیت</h3>
            <form action="{{ route('admin.designs.update', $customDesign) }}" method="POST">
                @csrf @method('PATCH')
                <div class="field">
                    <label>وضعیت</label>
                    <select name="status">
                        @foreach(['pending' => 'در انتظار بررسی', 'approved' => 'تأییدشده', 'in_progress' => 'در حال ساخت', 'completed' => 'آماده‌شده', 'rejected' => 'ردشده'] as $key => $label)
                            <option value="{{ $key }}" @selected($customDesign->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field" style="margin-top:12px">
                    <label>یادداشت مدیر (برای کاربر نمایش داده نمی‌شود)</label>
                    <textarea name="admin_note">{{ $customDesign->admin_note }}</textarea>
                </div>
                <button class="btn btn-primary btn-block" style="margin-top:12px">ذخیره ✅</button>
            </form>
        </div>
    </div>
@endsection
