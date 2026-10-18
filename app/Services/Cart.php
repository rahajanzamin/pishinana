<?php

namespace App\Services;

use App\Models\Product;

/**
 * سبد خرید مبتنی بر Session — برای کاربران مهمان هم کار می‌کند.
 */
class Cart
{
    private const SESSION_KEY = 'cart';

    /** همه‌ی اقلام سبد: [key => item] */
    public static function items(): array
    {
        return session(self::SESSION_KEY, []);
    }

    /** کلید یکتا برای هر قلم (محصول + رنگ) */
    public static function key(Product $product, ?string $color = null): string
    {
        return $product->id . '_' . md5($color ?? '');
    }

    public static function add(Product $product, int $qty = 1, ?string $color = null): void
    {
        $cart = self::items();
        $key = self::key($product, $color);

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'image' => $product->image,
                'color' => $color,
                'qty' => $qty,
            ];
        }

        session([self::SESSION_KEY => $cart]);
    }

    public static function update(string $key, int $qty): void
    {
        $cart = self::items();
        if (! isset($cart[$key])) {
            return;
        }
        if ($qty < 1) {
            unset($cart[$key]);
        } else {
            $cart[$key]['qty'] = $qty;
        }
        session([self::SESSION_KEY => $cart]);
    }

    public static function remove(string $key): void
    {
        $cart = self::items();
        unset($cart[$key]);
        session([self::SESSION_KEY => $cart]);
    }

    public static function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public static function count(): int
    {
        return array_sum(array_column(self::items(), 'qty'));
    }

    public static function total(): int
    {
        $sum = 0;
        foreach (self::items() as $item) {
            $sum += $item['price'] * $item['qty'];
        }
        return $sum;
    }
}
