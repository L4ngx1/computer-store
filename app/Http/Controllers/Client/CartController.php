<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Lấy dữ liệu giỏ hàng (từ Database nếu đã đăng nhập, từ Session nếu là khách)
     */
   private function getCart()
{
    if (Auth::check()) {
        // Lấy dữ liệu trực tiếp từ DB cho user hiện tại
        $items = \App\Models\CartItem::where('user_id', Auth::id())->get();
        
        // Chuyển thành mảng [product_id => quantity]
        // Chúng ta ép kiểu (int) để đảm bảo khớp với Product ID
        $cart = [];
        foreach ($items as $item) {
            $cart[(int)$item->product_id] = (int)$item->quantity;
        }
        
        return $cart;
    }
    return session('cart', []);
}
    /**
     * Hiển thị trang giỏ hàng
     */
    public function index()
{
    $cart = $this->getCart(); // Lấy mảng [product_id => quantity]
    $productIds = array_keys($cart);

    // 1. Lấy sản phẩm từ DB
    $products = Product::whereIn('id', $productIds)->get();

    // 2. Map dữ liệu an toàn
    $cartItems = $products->map(function ($product) use ($cart) {
        return (object) [
            'product' => $product,
            'quantity' => $cart[$product->id] ?? 0
        ];
    })->filter(function ($item) {
        // Chỉ lấy những sản phẩm có quantity > 0
        return $item->quantity > 0;
    });

    // 3. Tính tổng tiền
    $total = $cartItems->sum(function($item) {
        return ($item->product->sale_price ?? $item->product->price) * $item->quantity;
    });

    return view('client.cart', compact('cartItems', 'total'));
}

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function add($id)
    {
        $cart = $this->getCart();
        $quantity = ($cart[$id] ?? 0) + 1;

        if (Auth::check()) {
            CartItem::updateOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $id],
                ['quantity' => $quantity]
            );
        } else {
            $cart[$id] = $quantity;
            session(['cart' => $cart]);
        }

        return redirect()
            ->route('client.cart')
            ->with('cart_success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    /**
     * Cập nhật số lượng giỏ hàng
     */
    public function update(Request $request)
    {
        $cart = $this->getCart();

        if ($request->has('quantities')) {
            foreach ($request->quantities as $id => $qty) {
                if (Auth::check()) {
                    if ($qty > 0) {
                        CartItem::updateOrCreate(
                            ['user_id' => Auth::id(), 'product_id' => $id],
                            ['quantity' => $qty]
                        );
                    } else {
                        CartItem::where(['user_id' => Auth::id(), 'product_id' => $id])->delete();
                    }
                } else {
                    if ($qty > 0) {
                        $cart[$id] = $qty;
                    } else {
                        unset($cart[$id]);
                    }
                }
            }
        }
        
        if (!Auth::check()) {
            session(['cart' => $cart]);
        }

        return redirect()
            ->route('client.cart')
            ->with('cart_success', 'Giỏ hàng đã được cập nhật!');
    }

    /**
     * Xóa một sản phẩm
     */
    public function remove($id)
    {
        if (Auth::check()) {
            CartItem::where(['user_id' => Auth::id(), 'product_id' => $id])->delete();
        } else {
            $cart = session('cart', []);
            unset($cart[$id]);
            session(['cart' => $cart]);
        }

        return redirect()
            ->route('client.cart')
            ->with('cart_success', 'Đã xoá sản phẩm khỏi giỏ hàng!');
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }

        return redirect()
            ->route('client.cart')
            ->with('cart_success', 'Đã xoá toàn bộ giỏ hàng!');
    }
}