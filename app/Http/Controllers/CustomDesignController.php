<?php

namespace App\Http\Controllers;

use App\Models\CustomDesign;
use Illuminate\Http\Request;

class CustomDesignController extends Controller
{
    /** صفحه‌ی «طراحی اختصاصی» با شخصی‌ساز سه‌بعدی */
    public function index()
    {
        return view('custom.index');
    }

    /** ثبت درخواست طراحی */
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_type' => ['required', 'in:plush,bag,stationery,cosmetics,mug,gift'],
            'color' => ['nullable', 'string', 'max:30'],
            'custom_text' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('custom-designs', 'public');
        }

        CustomDesign::create([
            'user_id' => auth()->id(),
            'product_type' => $data['product_type'],
            'color' => $data['color'] ?? null,
            'custom_text' => $data['custom_text'] ?? null,
            'description' => $data['description'] ?? null,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'image_path' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()->route('custom.index')
            ->with('success', 'درخواست طراحیت ثبت شد! 💖 به‌زودی باهات تماس می‌گیریم.');
    }

    /** درخواست‌های من */
    public function mine()
    {
        $designs = auth()->user()->customDesigns()->latest()->paginate(10);

        return view('custom.mine', compact('designs'));
    }
}
