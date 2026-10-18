@extends('layouts.admin')

@section('title', 'پیام‌ها')

@section('content')
    <div class="admin-top"><h1>💌 پیام‌ها و ایده‌ها</h1></div>

    <div class="panel" style="padding:0; overflow-x:auto">
        <table class="data-table">
            <thead>
                <tr><th>نام</th><th>ایمیل</th><th>پیام</th><th>وضعیت</th><th>تاریخ</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                    <tr>
                        <td><b>{{ $message->name }}</b></td>
                        <td class="dim">{{ $message->email ?: '—' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($message->message, 50) }}</td>
                        <td>
                            <span class="status-badge {{ $message->is_read ? 'status-delivered' : 'status-pending' }}">
                                {{ $message->is_read ? 'خوانده‌شده' : 'جدید' }}
                            </span>
                        </td>
                        <td class="dim">{{ $message->created_at->format('Y/m/d') }}</td>
                        <td><a href="{{ route('admin.messages.show', $message) }}" class="btn btn-soft btn-small">مشاهده ←</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="dim" style="text-align:center">پیامی نیست</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $messages->links() }}</div>
@endsection
