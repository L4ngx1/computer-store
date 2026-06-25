<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['category', 'brand', 'images'])
            ->where('is_featured', true)
            ->where('is_active', true)
            ->take(8)
            ->get();

        $newProducts = Product::with(['category', 'brand', 'images'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $categories = Category::where('is_active', true)->take(6)->get();

        return view('client.home', compact('featuredProducts', 'newProducts', 'categories'));
    }
}
