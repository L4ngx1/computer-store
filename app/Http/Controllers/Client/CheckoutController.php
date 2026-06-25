<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $validated = $request->validate([
            'email'      => 'required|email',
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'company'    => 'nullable|string|max:255',
            'address'    => 'required|string|max:500',
            'city'       => 'required|string|max:255',
            'zip'        => 'required|string|max:20',
            'phone'      => ['required', 'regex:/^[0-9]{8,15}$/'],
            'shipping'   => 'required|in:standard,pickup',
        ]);

        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart')
                ->with('cart_error', 'Giỏ hàng trống!');
        }

        // Kiểm tra tồn kho trước khi tạo đơn
        foreach ($cartItems as $item) {
            if (! $item->product || ! $item->product->is_active) {
                return redirect()->route('client.cart')
                    ->with('cart_error', 'Một sản phẩm trong giỏ không còn kinh doanh.');
            }
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('client.cart')
                    ->with('cart_error', 'Sản phẩm "' . $item->product->name . '" chỉ còn ' . $item->product->stock . ' sản phẩm.');
            }
        }

        $order = DB::transaction(function () use ($validated, $cartItems) {
            $total = 0;

            $order = Order::create([
                'user_id'          => Auth::id(),
                'customer_name'    => $validated['first_name'] . ' ' . $validated['last_name'],
                'customer_email'   => $validated['email'],
                'customer_phone'   => $validated['phone'],
                'shipping_address' => $validated['address'] . ', ' . $validated['city'] . ' - ' . $validated['zip'],
                'note'             => 'Giao hàng: ' . ($validated['shipping'] === 'pickup' ? 'Nhận tại cửa hàng' : 'Giao hàng tiêu chuẩn'),
                'payment_method'   => 'COD',
                'status'           => 'pending',
                'total_amount'     => 0,
            ]);

            foreach ($cartItems as $item) {
                $price = $item->product->sale_price ?? $item->product->price;

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity'     => $item->quantity,
                    'price'        => $price,
                ]);

                // Trừ tồn kho
                Product::where('id', $item->product_id)->decrement('stock', $item->quantity);

                $total += $price * $item->quantity;
            }

            $order->update(['total_amount' => $total]);

            CartItem::where('user_id', Auth::id())->delete();

            return $order;
        });

        return redirect()->route('client.cart')
            ->with('checkout_success', 'Đặt hàng thành công! Mã đơn: #' . $order->id);
    }
}
