<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /** فروشگاه با فیلتر، جست‌وجو و مرتب‌سازی */
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        // جست‌وجو
        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // فیلتر دسته‌بندی (چندتایی)
        if ($request->filled('category')) {
            $query->whereIn('category_id', (array) $request->input('category'));
        }

        // فیلتر مجموعه (چندتایی)
        if ($request->filled('collection')) {
            $query->whereIn('collection_id', (array) $request->input('collection'));
        }

        // فیلتر رنگ
        if ($color = $request->input('color')) {
            $query->where('colors', 'like', "%{$color}%");
        }

        // محدوده قیمت
        if ($request->filled('min')) {
            $query->where('price', '>=', (int) $request->input('min'));
        }
        if ($request->filled('max')) {
            $query->where('price', '<=', (int) $request->input('max'));
        }

        // مرتب‌سازی
        $query->when($request->input('sort') === 'cheapest', fn ($q) => $q->orderBy('price'))
            ->when($request->input('sort') === 'expensive', fn ($q) => $q->orderByDesc('price'))
            ->when($request->input('sort') === 'newest', fn ($q) => $q->orderByDesc('id'))
            ->unless($request->filled('sort'), fn ($q) => $q->orderBy('sort')->orderByDesc('id'));

        return view('shop.index', [
            'products' => $query->paginate(12)->withQueryString(),
            'categories' => Category::withCount('products')->orderBy('sort')->get(),
            'collections' => Collection::withCount('products')->orderBy('sort')->get(),
        ]);
    }

    /** صفحه‌ی جزئیات محصول */
    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with('category', 'collection')
            ->firstOrFail();

        $related = Product::where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'related'));
    }
}
