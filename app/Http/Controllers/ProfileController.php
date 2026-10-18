<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /** پروفایل + علاقه‌مندی‌ها */
    public function edit()
    {
        $user = auth()->user();
        $favorites = $user->favorites()->latest()->get();

        return view('profile', compact('user', 'favorites'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // تغییر رمز (اختیاری)
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Password::min(6)],
            ]);
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        return back()->with('success', 'پروفایلت بروزرسانی شد ✨');
    }

    /** علاقه‌مندی‌ها: افزودن/حذف */
    public function toggleFavorite(Request $request, int $productId)
    {
        $user = auth()->user();
        $exists = $user->favorites()->where('product_id', $productId)->exists();

        if ($exists) {
            $user->favorites()->detach($productId);
            return back()->with('success', 'از علاقه‌مندی‌ها حذف شد 💔');
        }

        $user->favorites()->attach($productId);
        return back()->with('success', 'به علاقه‌مندی‌ها اضافه شد 💖');
    }
}
