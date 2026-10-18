<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomDesign extends Model
{
    protected $fillable = [
        'user_id', 'product_type', 'color', 'custom_text', 'description',
        'image_path', 'status', 'admin_note', 'name', 'phone',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function typeLabel(): string
    {
        return match ($this->product_type) {
            'plush' => 'عروسک',
            'bag' => 'کیف',
            'stationery' => 'لوازم تحریر',
            'cosmetics' => 'لوازم آرایشی',
            'mug' => 'ماگ',
            'gift' => 'هدیه',
            default => $this->product_type,
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'در انتظار بررسی',
            'approved' => 'تأییدشده',
            'in_progress' => 'در حال ساخت',
            'completed' => 'آماده‌شده',
            'rejected' => 'ردشده',
            default => $this->status,
        };
    }
}
