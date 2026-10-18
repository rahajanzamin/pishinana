<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'name', 'price', 'qty', 'color', 'options'];

    protected function casts(): array
    {
        return ['options' => 'array'];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function priceFormatted(): string
    {
        return number_format($this->price, 0, '', '٬') . ' تومان';
    }
}
