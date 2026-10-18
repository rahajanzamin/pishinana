<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('products')->orderBy('sort')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:10'],
            'image' => ['nullable', 'image', 'max:4096'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort' => ['nullable', 'integer'],
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . Str::lower(Str::random(4));
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return back()->with('success', 'دسته‌بندی ساخته شد ✅');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:10'],
            'image' => ['nullable', 'image', 'max:4096'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return back()->with('success', 'بروزرسانی شد ✅');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'حذف شد.');
    }
}
