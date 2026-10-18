<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->input('q') . '%'))
            ->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.form', [
            'product' => new Product(),
            'categories' => Category::orderBy('sort')->get(),
            'collections' => Collection::orderBy('sort')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['image'] = $this->storeImage($request);

        if ($request->filled('images_list')) {
            $data['images'] = $this->parseLines($request->input('images_list'));
        }
        if ($request->filled('colors_list')) {
            $data['colors'] = $this->parseLines($request->input('colors_list'));
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'محصول ساخته شد 🎉');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('sort')->get(),
            'collections' => Collection::orderBy('sort')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request);

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request);
        }
        if ($request->filled('images_list')) {
            $data['images'] = $this->parseLines($request->input('images_list'));
        }
        if ($request->filled('colors_list')) {
            $data['colors'] = $this->parseLines($request->input('colors_list'));
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'محصول بروزرسانی شد ✅');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'محصول حذف شد.');
    }

    // ---------- کمکی‌ها ----------

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'collection_id' => ['nullable', 'exists:collections,id'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_new' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'is_customizable' => ['nullable', 'boolean'],
            'sort' => ['nullable', 'integer'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);
    }

    private function storeImage(Request $request): ?string
    {
        return $request->hasFile('image')
            ? $request->file('image')->store('products', 'public')
            : null;
    }

    private function parseLines(string $text): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $text))));
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'product-' . time();
        $slug = $base;
        $i = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . (++$i);
        }
        return $slug;
    }
}
