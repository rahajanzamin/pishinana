@extends('layouts.admin')

@section('title', 'پیام')

@section('content')
    <div class="admin-top">
        <h1>💌 پیام از {{ $contactMessage->name }}</h1>
        <a href="{{ route('admin.messages.index') }}" class="btn btn-soft">← بازگشت</a>
    </div>

    <div class="panel">
        <p class="dim">{{ $contactMessage->created_at->format('Y/m/d - H:i') }}
            @if($contactMessage->email) • {{ $contactMessage->email }} @endif
        </p>
        <p style="margin-top:14px; white-space:pre-wrap">{{ $contactMessage->message }}</p>

        <form action="{{ route('admin.messages.destroy', $contactMessage) }}" method="POST" style="margin-top:20px">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-small" onclick="return confirm('حذف بشه؟')">🗑️ حذف پیام</button>
        </form>
    </div>
@endsection
