<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    public function home()
    {
        $newProducts = Product::where('stock', '>', 0)->latest()->take(6)->get();

        $msiLaptops = Product::where('name', 'LIKE', '%MSI%')
            ->latest()
            ->take(5)
            ->get();

        return view('client.home', compact('newProducts', 'msiLaptops'));
    }

    public function cart()
    {
        $cart = Session::get('cart', []);

        // Tính toán các thông số tiền tệ như trong ảnh thiết kế
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shipping = $subtotal > 0 ? 21.00 : 0.00; // Phí ship cố định $21.00 như thiết kế UI
        $tax = 1.91; // Thuế cố định theo UI mẫu
        $orderTotal = $subtotal + $shipping + $tax;

        return view('client.cart', compact('cart', 'subtotal', 'shipping', 'tax', 'orderTotal'));
    }

    public function checkout()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('client.cart')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $subtotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        return view('client.checkout', compact('cart', 'subtotal'));
    }

    public function accountDashboard()
    {
        $user = Auth::user();

        // Lấy các đơn hàng gần đây của User này để hiển thị trong mục "My Orders"
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('client.account.dashboard', compact('user', 'recentOrders'));
    }
}
