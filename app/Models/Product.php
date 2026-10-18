<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'collection_id', 'name', 'slug', 'description',
        'price', 'stock', 'image', 'images', 'colors',
        'is_new', 'is_active', 'is_customizable', 'sort',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'colors' => 'array',
            'is_new' => 'boolean',
            'is_active' => 'boolean',
            'is_customizable' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    /** قیمت با جداکننده‌ی هزارگان فارسی */
    public function priceFormatted(): string
    {
        return number_format($this->price, 0, '', '٬') . ' تومان';
    }
}
