<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Kiểm duyệt đánh giá sản phẩm trong khu vực quản trị.
 */
class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = ProductReview::with(['product:id,name,slug', 'user:id,name,email'])->latest();

        if ($request->filled('status')) {
            $query->where('is_approved', $request->input('status') === 'approved');
        }

        if ($request->filled('rating')) {
            $query->where('rating', (int) $request->input('rating'));
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', (int) $request->input('product_id'));
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($builder) use ($q) {
                $builder->where('comment', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($user) => $user
                        ->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%"))
                    ->orWhereHas('product', fn ($product) => $product
                        ->where('name', 'like', "%{$q}%"));
            });
        }

        $reviews = $query->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Duyệt một đánh giá.
     */
    public function approve(ProductReview $review): RedirectResponse
    {
        $review->update(['is_approved' => true]);

        return back()->with('success', 'Đã duyệt đánh giá.');
    }

    /**
     * Từ chối (gỡ khỏi công khai nhưng giữ bản ghi).
     */
    public function reject(ProductReview $review): RedirectResponse
    {
        $review->update(['is_approved' => false]);

        return back()->with('success', 'Đã chuyển đánh giá sang trạng thái chờ duyệt.');
    }

    /**
     * Xóa đánh giá không phù hợp.
     */
    public function destroy(ProductReview $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Đã xóa đánh giá.');
    }
}
