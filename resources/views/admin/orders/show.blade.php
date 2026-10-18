@extends('layouts.admin')

@section('title', 'سفارش ' . $order->order_number)

@section('content')
    <div class="admin-top">
        <h1>📦 سفارش {{ $order->order_number }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-soft">← بازگشت</a>
    </div>

    <div class="checkout-layout">
        <div class="panel" style="padding:0; overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr><th>کالا</th><th>قیمت</th><th>تعداد</th><th>جمع</th></tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td><b>{{ $item->name }}</b></td>
                            <td>{{ $item->priceFormatted() }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ number_format($item->price * $item->qty, 0, '', '٬') }} ت</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3"><b>جمع کل</b></td>
                        <td><b>{{ $order->priceFormatted() }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div>
            <div class="summary-card">
                <h3>تغییر وضعیت</h3>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="field">
                        <select name="status">
                            @foreach(['pending' => 'در انتظار پرداخت', 'paid' => 'پرداخت‌شده', 'processing' => 'در حال آماده‌سازی', 'shipped' => 'ارسال‌شده', 'delivered' => 'تحویل‌شده', 'cancelled' => 'لغوشده'] as $key => $label)
                                <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary btn-block" style="margin-top:12px">ذخیره ✅</button>
                </form>
            </div>

            <div class="summary-card" style="margin-top:14px">
                <h3>اطلاعات</h3>
                <p>👤 {{ $order->recipient_name }} ({{ $order->user->email }})</p>
                <p>📞 {{ $order->phone }}</p>
                <p>📍 {{ $order->address }}</p>
                @if($order->payment_ref)
                    <p>💳 کد پیگیری: {{ $order->payment_ref }}</p>
                @endif
                @if($order->note)
                    <p class="dim">📝 {{ $order->note }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
