@extends('layouts.admin')

@section('title', 'سفارش‌ها')

@section('content')
    <div class="admin-top">
        <h1>📦 سفارش‌ها</h1>
        <div style="display:flex; gap:8px">
            @foreach(['' => 'همه', 'pending' => 'در انتظار', 'paid' => 'پرداخت‌شده', 'shipped' => 'ارسال‌شده', 'delivered' => 'تحویل‌شده'] as $val => $label)
                <a href="{{ route('admin.orders.index', ['status' => $val]) }}"
                   class="btn btn-small {{ request('status') === $val ? 'btn-primary' : 'btn-soft' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="panel" style="padding:0; overflow-x:auto">
        <table class="data-table">
            <thead>
                <tr><th>شماره</th><th>کاربر</th><th>مبلغ</th><th>وضعیت</th><th>تاریخ</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><b>{{ $order->order_number }}</b></td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->priceFormatted() }}</td>
                        <td><span class="status-badge status-{{ $order->status }}">{{ $order->statusLabel() }}</span></td>
                        <td class="dim">{{ $order->created_at->format('Y/m/d - H:i') }}</td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-soft btn-small">مشاهده ←</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="dim" style="text-align:center">سفارشی یافت نشد</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $orders->links() }}</div>
@endsection
