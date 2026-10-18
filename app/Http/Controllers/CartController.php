<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index', [
            'items' => Cart::items(),
            'total' => Cart::total(),
        ]);
    }

    public function add(Request $request, Product $product)
    {
        $data = $request->validate([
            'qty' => ['nullable', 'integer', 'min:1', 'max:20'],
            'color' => ['nullable', 'string', 'max:30'],
        ]);

        Cart::add($product, (int) ($data['qty'] ?? 1), $data['color'] ?? null);

        return back()->with('success', "«{$product->name}» به سبد اضافه شد 🛒");
    }

    public function update(Request $request, string $key)
    {
        $request->validate(['qty' => ['required', 'integer', 'min:0', 'max:20']]);
        Cart::update($key, (int) $request->input('qty'));

        return back()->with('success', 'سبد خرید بروزرسانی شد.');
    }

    public function destroy(string $key)
    {
        Cart::remove($key);
        return back()->with('success', 'کالا از سبد حذف شد.');
    }
}
