<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'total', 'status', 'payment_method',
        'payment_authority', 'payment_ref', 'recipient_name', 'phone',
        'address', 'postal_code', 'note', 'paid_at',
    ];

    protected function casts(): array
    {
        return ['paid_at' => 'datetime'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function priceFormatted(): string
    {
        return number_format($this->total, 0, '', '٬') . ' تومان';
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'در انتظار پرداخت',
            'paid' => 'پرداخت‌شده',
            'processing' => 'در حال آماده‌سازی',
            'shipped' => 'ارسال‌شده',
            'delivered' => 'تحویل‌شده',
            'cancelled' => 'لغوشده',
            default => $this->status,
        };
    }

    public static function generateOrderNumber(): string
    {
        return 'PSH-' . random_int(100000, 999999);
    }
}
