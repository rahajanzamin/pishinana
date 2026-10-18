<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    public function index()
    {
        return view('admin.collections.index', [
            'collections' => Collection::withCount('products')->orderBy('sort')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'max:4096'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort' => ['nullable', 'integer'],
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . Str::lower(Str::random(4));
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('collections', 'public');
        }

        Collection::create($data);

        return back()->with('success', 'مجموعه ساخته شد ✅');
    }

    public function update(Request $request, Collection $collection)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'max:4096'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('collections', 'public');
        }

        $collection->update($data);

        return back()->with('success', 'بروزرسانی شد ✅');
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();
        return back()->with('success', 'حذف شد.');
    }
}
