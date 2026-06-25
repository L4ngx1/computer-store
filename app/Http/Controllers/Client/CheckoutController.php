<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('client.checkout', compact('cartItems'));
    }

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
            'phone'      => ['required', 'regex:/^[0-9]+$/'],
            'shipping'   => 'required|in:standard,pickup',
        ]);

        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('client.cart')
                ->with('cart_error', 'Giỏ hàng trống!');
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $request->first_name . ' ' . $request->last_name,
            'customer_email' => $request->email,
            'customer_phone' => $request->phone,
            'shipping_address' => $request->address . ', ' . $request->city . ' - ' . $request->zip,
            'payment_method' => $request->shipping,
            'status' => 'pending',
            'total_amount' => 0,
        ]);

        $total = 0;

        foreach ($cartItems as $item) {

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);

            $total += $item->product->price * $item->quantity;
        }

        $order->update([
            'total_amount' => $total
        ]);

        CartItem::where('user_id', Auth::id())->delete();

        return redirect()
            ->route('client.cart')
            ->with('checkout_success', 'Đặt hàng thành công!');
    }
}