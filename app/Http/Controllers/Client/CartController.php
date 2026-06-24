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
            return CartItem::where('user_id', Auth::id())->pluck('quantity', 'product_id')->toArray();
        }
        return session('cart', []);
    }

    public function index()
    {
        $cart = $this->getCart();

        if (env('CART_DEMO') && empty($cart)) {
            $cart = [
                1 => 1,
                2 => 2,
                3 => 1,
            ];
            if (! Auth::check()) {
                session(['cart' => $cart]);
            }
        }

        $products = Product::whereIn('id', array_keys($cart))->get();

        return view('client.cart', compact('cart', 'products'));
    }

    // =====================
    // ADD TO CART
    // =====================
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

    // =====================
    // UPDATE CART
    // =====================
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

    // =====================
    // REMOVE ITEM
    // =====================
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

    // =====================
    // CLEAR CART
    // =====================
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