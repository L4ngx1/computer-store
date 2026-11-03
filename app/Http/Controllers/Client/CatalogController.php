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
        $query = Product::with(['category:id,name,slug', 'brand:id,name,slug'])->where('is_active', true);

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

        // Thống kê đánh giá đã duyệt (hiển thị công khai)
        $approvedReviews = $product->approvedReviews()->with('user:id,name')->latest();
        $reviewStats = [
            'average' => round((float) $product->approvedReviews()->avg('rating'), 1),
            'total' => $product->approvedReviews()->count(),
            'distribution' => $product->approvedReviews()
                ->selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')
                ->pluck('count', 'rating')
                ->all(),
        ];
        $reviews = $approvedReviews->paginate(5, ['*'], 'review_page');

        // Huy hiệu "Đã mua hàng" chỉ khi người dùng đã mua (đơn hoàn tất)
        $hasPurchased = auth()->check() ? auth()->user()->hasPurchasedProduct($product->id) : false;

        // Đánh giá của người dùng hiện tại (nếu có) để chỉnh sửa/xóa
        $myReview = auth()->check()
            ? $product->reviews()->where('user_id', auth()->id())->first()
            : null;

        // Trạng thái yêu thích của người dùng hiện tại
        $isWishlisted = auth()->check()
            ? $product->wishlistedBy()->where('user_id', auth()->id())->exists()
            : false;

        $relatedProducts = Product::with(['images', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('client.product', compact('product', 'relatedProducts', 'reviews', 'reviewStats', 'hasPurchased', 'myReview'));
    }

}