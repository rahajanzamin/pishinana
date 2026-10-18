<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'categories' => Category::orderBy('sort')->get(),
            'collections' => Collection::orderBy('sort')->get(),
            'newProducts' => Product::where('is_active', true)
                ->where('is_new', true)
                ->orderByDesc('id')
                ->take(8)
                ->get(),
            'customizable' => Product::where('is_active', true)
                ->where('is_customizable', true)
                ->orderBy('sort')
                ->take(6)
                ->get(),
        ]);
    }
}
