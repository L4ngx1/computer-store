<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index()
    {
        $products = Product::with(['category', 'brand'])
            ->latest()
            ->paginate(15);
        
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        
        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'images', 'attributes']);
        
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $product->load(['category', 'brand', 'images', 'attributes']);
        $categories = Category::all();
        $brands = Brand::all();
        
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }
}
