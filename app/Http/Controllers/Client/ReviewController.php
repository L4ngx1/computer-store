<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Quản lý đánh giá sản phẩm của khách hàng.
 */
class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Chỉ tài khoản đã xác minh email mới được gửi đánh giá
        $this->middleware('verified')->except(['index']);
    }

    /**
     * Danh sách đánh giá của tôi.
     */
    public function index()
    {
        $reviews = Auth::user()->reviews()
            ->with('product:id,name,slug')
            ->latest()
            ->paginate(10);

        return view('client.reviews', compact('reviews'));
    }

    /**
     * Gửi đánh giá mới cho sản phẩm.
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $throttleKey = 'review-store:' . Auth::id() . ':' . $product->id;
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            return back()->with('error', 'Bạn đã thao tác quá nhiều lần. Vui lòng thử lại sau ít phút.');
        }
        RateLimiter::hit($throttleKey, 300);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.integer' => 'Điểm đánh giá không hợp lệ.',
            'rating.min' => 'Điểm đánh giá thấp nhất là 1 sao.',
            'rating.max' => 'Điểm đánh giá cao nhất là 5 sao.',
            'comment.required' => 'Vui lòng nhập nội dung đánh giá.',
            'comment.min' => 'Nội dung đánh giá cần ít nhất 10 ký tự.',
            'comment.max' => 'Nội dung đánh giá không được vượt quá 2000 ký tự.',
        ]);

        // Mỗi người dùng chỉ được đánh giá một sản phẩm một lần
        if (Auth::user()->reviews()->where('product_id', $product->id)->exists()) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        Auth::user()->reviews()->create([
            'product_id' => $product->id,
            'rating' => $validated['rating'],
            'comment' => strip_tags($validated['comment']),
            'is_approved' => false,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá! Đánh giá của bạn đang chờ kiểm duyệt.');
    }

    /**
     * Cập nhật đánh giá của chính mình.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $review = Auth::user()->reviews()->findOrFail($id);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.min' => 'Điểm đánh giá thấp nhất là 1 sao.',
            'rating.max' => 'Điểm đánh giá cao nhất là 5 sao.',
            'comment.required' => 'Vui lòng nhập nội dung đánh giá.',
            'comment.min' => 'Nội dung đánh giá cần ít nhất 10 ký tự.',
            'comment.max' => 'Nội dung đánh giá không được vượt quá 2000 ký tự.',
        ]);

        $review->update([
            'rating' => $validated['rating'],
            'comment' => strip_tags($validated['comment']),
            // Sửa đánh giá phải qua kiểm duyệt lại
            'is_approved' => false,
        ]);

        return back()->with('success', 'Đã cập nhật đánh giá. Đánh giá của bạn đang chờ kiểm duyệt lại.');
    }

    /**
     * Xóa đánh giá của chính mình.
     */
    public function destroy(int $id): RedirectResponse
    {
        $review = Auth::user()->reviews()->findOrFail($id);
        $review->delete();

        return back()->with('success', 'Đã xóa đánh giá của bạn.');
    }
}
