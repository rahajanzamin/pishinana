<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomDesign;
use Illuminate\Http\Request;

class CustomDesignController extends Controller
{
    public function index(Request $request)
    {
        $designs = CustomDesign::with('user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->latest()->paginate(15);

        return view('admin.designs.index', compact('designs'));
    }

    public function show(CustomDesign $customDesign)
    {
        $customDesign->load('user');
        return view('admin.designs.show', compact('customDesign'));
    }

    public function update(Request $request, CustomDesign $customDesign)
    {
        $request->validate([
            'status' => ['required', 'in:pending,approved,in_progress,completed,rejected'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $customDesign->update($request->only('status', 'admin_note'));

        return back()->with('success', 'بروزرسانی شد ✅');
    }
}
