@extends('layouts.app')

@section('title', 'طراحی‌های من | پیشینانا')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>🎨 طراحی‌های من</h1>
            <p class="breadcrumb"><a href="{{ route('home') }}">خانه</a> / طراحی‌های من</p>
        </div>

        @if($designs->count())
            <div class="panel" style="padding:0; overflow-x:auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>محصول</th>
                            <th>نوشته</th>
                            <th>رنگ</th>
                            <th>تاریخ</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($designs as $design)
                            <tr>
                                <td><b>{{ $design->typeLabel() }}</b></td>
                                <td>{{ $design->custom_text ?: '—' }}</td>
                                <td>
                                    @if($design->color)
                                        <span class="dot" style="display:inline-block;width:18px;height:18px;border-radius:50%;background:{{ $design->color }};vertical-align:middle"></span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="dim">{{ $design->created_at->format('Y/m/d') }}</td>
                                <td>
                                    <span class="status-badge status-{{ in_array($design->status, ['approved', 'completed']) ? 'paid' : ($design->status === 'rejected' ? 'cancelled' : 'pending') }}">
                                        {{ $design->statusLabel() }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination">{{ $designs->links() }}</div>
        @else
            <div class="empty-state">
                <span class="big">🎨</span>
                هنوز طراحی‌ای ثبت نکردی!
                <br>
                <a href="{{ route('custom.index') }}" class="btn btn-primary" style="margin-top:14px">شروع طراحی ✨</a>
            </div>
        @endif
    </div>
@endsection
