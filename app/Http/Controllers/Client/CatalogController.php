<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images'])->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('brand')) {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc': $query->orderBy('price', 'asc'); break;
                case 'price_desc': $query->orderBy('price', 'desc'); break;
                case 'newest': $query->orderBy('created_at', 'desc'); break;
                default: $query->orderBy('created_at', 'desc'); break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::all();

        return view('client.catalog', compact('products', 'categories', 'brands'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'brand', 'images', 'attributes'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::with(['images', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('client.product', compact('product', 'relatedProducts'));
    }

    public function addToCart(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login.form')
            ->with('error', 'Vui lòng đăng nhập để thêm vào giỏ hàng.');
    }

    $request->validate([
        'product_id' => 'required|exists:products,id'
    ]);

    $item = CartItem::where('user_id', Auth::id())
        ->where('product_id', $request->product_id)
        ->first();

    if ($item) {
        $item->quantity += 1;
        $item->save();
    } else {
        CartItem::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'quantity' => 1
        ]);
    }

    return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
}
}