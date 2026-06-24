<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        if (env('CART_DEMO') && empty($cart)) {
            $cart = [
                1 => 1,
                2 => 2,
                3 => 1
            ];

            session(['cart' => $cart]);
        }

        $products = Product::whereIn('id', array_keys($cart))->get();

        return view('client.cart', compact('cart', 'products'));
    }

    // =====================
    // ADD TO CART
    // =====================
    public function add($id)
    {
        $cart = session('cart', []);

        $cart[$id] = ($cart[$id] ?? 0) + 1;

        session(['cart' => $cart]);

        return redirect()
            ->route('client.cart')
            ->with('cart_success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    // =====================
    // UPDATE CART
    // =====================
    public function update(Request $request)
    {
        $cart = session('cart', []);

        if ($request->has('quantities')) {
            foreach ($request->quantities as $id => $qty) {
                if ($qty > 0) {
                    $cart[$id] = $qty;
                } else {
                    unset($cart[$id]);
                }
            }
        }

        session(['cart' => $cart]);

        return redirect()
            ->route('client.cart')
            ->with('cart_success', 'Giỏ hàng đã được cập nhật!');
    }

    // =====================
    // REMOVE ITEM
    // =====================
    public function remove($id)
    {
        $cart = session('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        session(['cart' => $cart]);

        return redirect()
            ->route('client.cart')
            ->with('cart_success', 'Đã xoá sản phẩm khỏi giỏ hàng!');
    }

    // =====================
    // CLEAR CART
    // =====================
    public function clear()
    {
        session()->forget('cart');

        return redirect()
            ->route('client.cart')
            ->with('cart_success', 'Đã xoá toàn bộ giỏ hàng!');
    }
}