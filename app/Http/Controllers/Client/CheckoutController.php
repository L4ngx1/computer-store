<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    // =====================
    // SHOW CHECKOUT PAGE (FIX THIẾU)
    // =====================
    public function index()
    {
        $cart = session('cart', []);

        $products = Product::whereIn('id', array_keys($cart))->get();

        return view('client.checkout', compact('products'));
    }

    // =====================
    // STORE ORDER
    // =====================
    public function store(Request $request)
    {
        $request->validate([
            'email'      => 'required|email',
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'company'    => 'required|string',
            'address'    => 'required|string',
            'city'       => 'required|string',
            'zip'        => 'required|string',
            'phone'      => ['required','regex:/^[0-9]+$/'],
            'shipping'   => 'required|in:standard,pickup',
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('client.cart')
                ->with('cart_error', 'Giỏ hàng trống!');
        }

        $products = Product::whereIn('id', array_keys($cart))->get();

        $order = Order::create([
            'user_id' => auth()->id(),
            'customer_name' => $request->first_name . ' ' . $request->last_name,
            'customer_email' => $request->email,
            'customer_phone' => $request->phone,
            'shipping_address' => $request->address . ', ' . $request->city . ' - ' . $request->zip,
            'payment_method' => $request->shipping,
            'status' => 'pending',
            'total_amount' => 0,
        ]);

        $total = 0;

        foreach ($products as $product) {
            $qty = $cart[$product->id];
            $subtotal = $product->price * $qty;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $qty,
                'price' => $product->price,
            ]);

            $total += $subtotal;
        }

        $order->update(['total_amount' => $total]);

        session()->forget('cart');

        return redirect()
            ->route('client.cart')
            ->with('checkout_success', 'Đặt hàng thành công!');
    }
}