@extends('layouts.admin')

@section('title', 'داشبورد')

@section('content')
    <div class="admin-top">
        <h1>📊 داشبورد مدیریت</h1>
        <span class="dim">{{ auth()->user()->name }} عزیز، خوش آمدی 🌸</span>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="num">{{ $productCount }}</div>
            <div class="label">🛍️ محصولات</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ $orderCount }}</div>
            <div class="label">📦 کل سفارش‌ها</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ $pendingOrders }}</div>
            <div class="label">⏳ سفارش در انتظار</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ number_format($revenue, 0, '', '٬') }}</div>
            <div class="label">💰 درآمد (تومان)</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ $userCount }}</div>
            <div class="label">👥 کاربران</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ $newDesigns }}</div>
            <div class="label">🎨 طراحی جدید</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ $unreadMessages }}</div>
            <div class="label">💌 پیام خوانده‌نشده</div>
        </div>
        <div class="stat-card">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-block">➕ محصول جدید</a>
        </div>
    </div>

    <div class="panel" style="padding:0; overflow-x:auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>شماره</th>
                    <th>کاربر</th>
                    <th>مبلغ</th>
                    <th>وضعیت</th>
                    <th>تاریخ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestOrders as $order)
                    <tr>
                        <td><b>{{ $order->order_number }}</b></td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->priceFormatted() }}</td>
                        <td><span class="status-badge status-{{ $order->status }}">{{ $order->statusLabel() }}</span></td>
                        <td class="dim">{{ $order->created_at->format('Y/m/d - H:i') }}</td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-soft btn-small">مشاهده</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="dim" style="text-align:center">هنوز سفارشی نیست</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
