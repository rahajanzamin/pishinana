@extends('layouts.admin')

@section('title', 'طراحی‌های سفارشی')

@section('content')
    <div class="admin-top"><h1>🎨 طراحی‌های سفارشی</h1></div>

    <div class="panel" style="padding:0; overflow-x:auto">
        <table class="data-table">
            <thead>
                <tr><th>کاربر</th><th>محصول</th><th>نوشته</th><th>رنگ</th><th>وضعیت</th><th>تاریخ</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($designs as $design)
                    <tr>
                        <td>{{ $design->name ?: ($design->user->name ?? '—') }}</td>
                        <td><b>{{ $design->typeLabel() }}</b></td>
                        <td>{{ $design->custom_text ?: '—' }}</td>
                        <td>
                            @if($design->color)
                                <span style="display:inline-block;width:18px;height:18px;border-radius:50%;background:{{ $design->color }};vertical-align:middle"></span>
                            @else — @endif
                        </td>
                        <td><span class="status-badge status-{{ in_array($design->status, ['approved', 'completed']) ? 'paid' : ($design->status === 'rejected' ? 'cancelled' : 'pending') }}">{{ $design->statusLabel() }}</span></td>
                        <td class="dim">{{ $design->created_at->format('Y/m/d') }}</td>
                        <td><a href="{{ route('admin.designs.show', $design) }}" class="btn btn-soft btn-small">بررسی ←</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="dim" style="text-align:center">درخواستی نیست</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $designs->links() }}</div>
@endsection
