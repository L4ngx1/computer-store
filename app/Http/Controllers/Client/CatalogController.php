<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\CartItem; // Import Model giỏ hàng
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    // Hiển thị danh sách sản phẩm
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
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();
        
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::all();

        return view('client.catalog', compact('products', 'categories', 'brands'));
    }

    // Hiển thị chi tiết sản phẩm
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

    // Xử lý thêm vào giỏ hàng
    public function addToCart(Request $request)
    {
        // 1. Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Vui lòng đăng nhập để thêm vào giỏ hàng.');
        }

        // 2. Kiểm tra dữ liệu
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // 3. Logic: Cộng dồn số lượng nếu đã có, tạo mới nếu chưa có
        CartItem::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => DB::raw('quantity + 1')
            ]
        );

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }
}