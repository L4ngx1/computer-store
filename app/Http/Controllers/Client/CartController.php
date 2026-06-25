<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function getCart()
    {
        if (Auth::check()) {
            $items = CartItem::where('user_id', Auth::id())->get();
            $cart = [];
            foreach ($items as $item) {
                $cart[(int)$item->product_id] = (int)$item->quantity;
            }
            return $cart;
        }
        return session('cart', []);
    }

    public function index()
    {
        $cart = $this->getCart();
        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get();

        $cartItems = $products->map(function ($product) use ($cart) {
            return (object) [
                'product' => $product,
                'quantity' => $cart[$product->id] ?? 0
            ];
        })->filter(function ($item) {
            return $item->quantity > 0;
        });

        $total = $cartItems->sum(function($item) {
            return ($item->product->sale_price ?? $item->product->price) * $item->quantity;
        });

        return view('client.cart', compact('cartItems', 'total'));
    }

    // Hàm add nhận Request, xử lý xong sẽ redirect về giỏ hàng
    public function add(Request $request)
    {
        $id = $request->input('product_id');

        if (!$id) {
            return redirect()->back()->with('error', 'Sản phẩm không hợp lệ!');
        }

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

        return redirect()->route('client.cart')->with('cart_success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function update(Request $request)
    {
        $cart = $this->getCart();
        if ($request->has('quantities')) {
            foreach ($request->quantities as $id => $qty) {
                if (Auth::check()) {
                    if ($qty > 0) {
                        CartItem::updateOrCreate(['user_id' => Auth::id(), 'product_id' => $id], ['quantity' => $qty]);
                    } else {
                        CartItem::where(['user_id' => Auth::id(), 'product_id' => $id])->delete();
                    }
                } else {
                    if ($qty > 0) { $cart[$id] = $qty; } else { unset($cart[$id]); }
                }
            }
        }
        if (!Auth::check()) { session(['cart' => $cart]); }
        return redirect()->route('client.cart')->with('cart_success', 'Giỏ hàng đã được cập nhật!');
    }

    public function remove($id)
    {
        if (Auth::check()) {
            CartItem::where(['user_id' => Auth::id(), 'product_id' => $id])->delete();
        } else {
            $cart = session('cart', []);
            unset($cart[$id]);
            session(['cart' => $cart]);
        }
        return redirect()->route('client.cart')->with('cart_success', 'Đã xoá sản phẩm!');
    }

    public function clear()
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }
        return redirect()->route('client.cart')->with('cart_success', 'Đã xoá giỏ hàng!');
    }
}