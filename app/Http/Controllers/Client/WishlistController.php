<?php

namespace App\Http\Controllers\Client;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Thêm hoặc xóa sản phẩm khỏi wishlist (toggle).
     */
    public function toggle(Product $product): JsonResponse|RedirectResponse
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $message = 'Đã xóa khỏi danh sách yêu thích.';
            $added = false;
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);
            $message = 'Đã thêm vào danh sách yêu thích.';
            $added = true;
        }

        if (request()->wantsJson()) {
            return response()->json(['added' => $added, 'message' => $message]);
        }

        return back()->with('success', $message);
    }

    /**
     * Xem danh sách yêu thích.
     */
    public function index()
    {
        $wishlists = Auth::user()->wishlist()
            ->with('product:id,name,slug,thumbnail,price,sale_price,stock')
            ->latest()
            ->paginate(12);

        return view('client.wishlist', compact('wishlists'));
    }
}
