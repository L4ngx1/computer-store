<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(15)->withQueryString();


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

    /**
     * Store a newly created product in storage
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'sale_price' => 'nullable|numeric|min:0|lte:price',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'nullable|string',
            'summary' => 'nullable|string|max:500',
            'stock' => 'nullable|integer|min:0',
            'warranty_start_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date|after_or_equal:warranty_start_date',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $validatedData['slug'] = $this->uniqueSlug($request->name);
        $validatedData['sku'] = $this->uniqueSku();

        if ($request->hasFile('thumbnail')) {
            $validatedData['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        Product::create($validatedData);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Update the specified product in storage
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:price',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'summary' => 'nullable|string|max:500',
            'stock' => 'nullable|integer|min:0',
            'warranty_start_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date|after_or_equal:warranty_start_date',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $validatedData['slug'] = $this->uniqueSlug($request->name, $product->id);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if it exists
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $validatedData['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        $product->update($validatedData);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage
     */
    public function destroy(Product $product)
    {
        // ToDo: Implement deletion logic
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $suffix = 2;

        while (Product::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function uniqueSku(): string
    {
        do {
            $sku = 'SKU-' . strtoupper(Str::random(8));
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }
}
