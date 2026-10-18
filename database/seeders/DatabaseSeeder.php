<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── کاربران ─────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@pishinana.ir'],
            ['name' => 'مدیر پیشینانا', 'password' => 'password', 'is_admin' => true, 'phone' => '09120000000']
        );

        User::updateOrCreate(
            ['email' => 'user@pishinana.ir'],
            ['name' => 'کاربر نمونه', 'password' => 'password', 'phone' => '09121112233']
        );

        // ── دسته‌بندی‌ها ─────────────────────────────────────────
        $categories = collect([
            ['name' => 'عروسک‌ها', 'icon' => '🧸', 'image' => 'images/cat-dolls.png', 'description' => 'عروسک‌های نرم و بامزه'],
            ['name' => 'کیف و کیف‌پول', 'icon' => '👜', 'image' => 'images/cat-bags.png', 'description' => 'کیف‌های فانتزی و دوست‌داشتنی'],
            ['name' => 'لوازم آرایشی', 'icon' => '💄', 'image' => 'images/cat-cosmetics.png', 'description' => 'محصولات مراقبتی و آرایشی بامزه'],
            ['name' => 'لوازم تحریر', 'icon' => '✏️', 'image' => 'images/cat-stationery.png', 'description' => 'دفتر، خودکار و ابزار نوشتن'],
            ['name' => 'ماگ و ظروف', 'icon' => '☕', 'image' => 'images/cat-mugs.png', 'description' => 'ماگ و ظروف کوچولوی خوشگل'],
            ['name' => 'هدیه', 'icon' => '🎁', 'image' => 'images/cat-gifts.png', 'description' => 'کادوهای آماده و خاص'],
        ])->map(fn ($c) => Category::updateOrCreate(
            ['slug' => Str::slug($c['name'])],
            $c + ['sort' => 0]
        ));

        // ── مجموعه‌ها ───────────────────────────────────────────
        $collections = collect([
            ['name' => 'بهار و طبیعت', 'image' => 'images/col-spring.png', 'description' => 'حال‌وهوای تازه‌ی بهاری'],
            ['name' => 'اکسسوری دخترانه', 'image' => 'images/col-accessories.png', 'description' => 'تکمیل‌کننده‌ی استایل تو'],
            ['name' => 'شاهزاده رویایی', 'image' => 'images/col-princess.png', 'description' => 'رویاها رنگ واقعی می‌گیرند'],
            ['name' => 'ترند کوچولو', 'image' => 'images/col-trend.png', 'description' => 'محبوب‌ترین‌های این فصل'],
        ])->map(fn ($c) => Collection::updateOrCreate(
            ['slug' => Str::slug($c['name'])],
            $c + ['sort' => 0]
        ));

        // ── محصولات ─────────────────────────────────────────────
        $products = [
            ['name' => 'عروسک خرس مهربون', 'category' => 'عروسک‌ها', 'collection' => 'شاهزاده رویایی', 'price' => 499000, 'image' => 'images/p-bear.png', 'colors' => ['#f8c8dc', '#fff', '#c9a0dc'], 'is_new' => true],
            ['name' => 'عروسک گربه‌ی ناز', 'category' => 'عروسک‌ها', 'collection' => 'ترند کوچولو', 'price' => 389000, 'image' => 'images/p-cat.png', 'colors' => ['#fff', '#f8c8dc'], 'is_new' => true],
            ['name' => 'کوله‌پشتی پاپیونی', 'category' => 'کیف و کیف‌پول', 'collection' => 'اکسسوری دخترانه', 'price' => 789000, 'image' => 'images/p-bag.png', 'colors' => ['#f8c8dc', '#f5b7cd', '#fff'], 'is_new' => true],
            ['name' => 'کیف‌پول قلبی', 'category' => 'کیف و کیف‌پول', 'collection' => 'اکسسوری دخترانه', 'price' => 349000, 'image' => 'images/cat-bags.png', 'colors' => ['#e2527f', '#f8c8dc']],
            ['name' => 'ماگ گربه‌ای', 'category' => 'ماگ و ظروف', 'collection' => 'بهار و طبیعت', 'price' => 259000, 'image' => 'images/p-mug.png', 'colors' => ['#fff', '#f8c8dc'], 'is_new' => true],
            ['name' => 'ست ماگ و زیرلیوانی', 'category' => 'ماگ و ظروف', 'collection' => 'ترند کوچولو', 'price' => 429000, 'image' => 'images/cat-mugs.png', 'colors' => ['#f8c8dc']],
            ['name' => 'دفترچه‌ی خاطرات', 'category' => 'لوازم تحریر', 'collection' => 'بهار و طبیعت', 'price' => 189000, 'image' => 'images/p-notebook.png', 'colors' => ['#f8c8dc', '#fff', '#c9a0dc']],
            ['name' => 'ست لوازم تحریر', 'category' => 'لوازم تحریر', 'collection' => 'ترند کوچولو', 'price' => 399000, 'image' => 'images/cat-stationery.png', 'colors' => ['#f8c8dc'], 'is_new' => true],
            ['name' => 'پالت سایه‌ی گلی', 'category' => 'لوازم آرایشی', 'collection' => 'اکسسوری دخترانه', 'price' => 559000, 'image' => 'images/p-palette.png', 'colors' => ['#f8c8dc', '#e2527f']],
            ['name' => 'کیف لوازم آرایش', 'category' => 'لوازم آرایشی', 'collection' => 'شاهزاده رویایی', 'price' => 319000, 'image' => 'images/cat-cosmetics.png', 'colors' => ['#f8c8dc']],
            ['name' => 'جعبه‌ی کادویی قلبی', 'category' => 'هدیه', 'collection' => 'شاهزاده رویایی', 'price' => 459000, 'image' => 'images/p-gift.png', 'colors' => ['#e2527f', '#f8c8dc'], 'is_new' => true],
            ['name' => 'ست هدیه‌ی کوچولو', 'category' => 'هدیه', 'collection' => 'بهار و طبیعت', 'price' => 699000, 'image' => 'images/p-giftset.png', 'colors' => ['#f8c8dc', '#fff']],
        ];

        foreach ($products as $i => $p) {
            $category = $categories->firstWhere('name', $p['category']);
            $collection = $collections->firstWhere('name', $p['collection']);

            Product::updateOrCreate(
                ['slug' => Str::slug($p['name'])],
                [
                    'category_id' => $category->id,
                    'collection_id' => $collection?->id,
                    'name' => $p['name'],
                    'description' => 'یک محصول کوچولو و دوست‌داشتنی از مجموعه‌ی پیشینانا. ساخته‌شده با عشق، مناسب هدیه دادن به خودت و عزیزانت. 🎀',
                    'price' => $p['price'],
                    'stock' => random_int(5, 40),
                    'image' => $p['image'],
                    'colors' => $p['colors'],
                    'is_new' => $p['is_new'] ?? false,
                    'is_active' => true,
                    'is_customizable' => in_array($p['category'], ['عروسک‌ها', 'کیف و کیف‌پول', 'لوازم تحریر', 'ماگ و ظروف'], true),
                    'sort' => $i,
                ]
            );
        }
    }
}
