<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    // 1. TRANG CHỦ (Home - 1.jpg)
    public function home()
    {
        // Lấy danh sách sản phẩm mới nhất để hiển thị ở các hàng "New Products"
        $newProducts = Product::where('stock', '>', 0)->latest()->take(6)->get();

        // Lấy sản phẩm nổi bật (Featured) theo danh mục cụ thể (ví dụ: MSI Laptops)
        $msiLaptops = Product::where('name', 'LIKE', '%MSI%')
            ->latest()
            ->take(5)
            ->get();

        return view('client.home', compact('newProducts', 'msiLaptops'));
    }

    // 2. TRANG GIỎ HÀNG (Shopping Cart - 1.png)
    public function cart()
    {
        // Lấy giỏ hàng từ Session (Vì tự code thuần không dùng thư viện ngoài)
        $cart = session()->get('cart', []);

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

    // 3. TRANG THANH TOÁN (Checkout - 1.png)
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('client.cart')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $subtotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        return view('client.checkout', compact('cart', 'subtotal'));
    }

    // 4. TRANG DASHBOARD USER ACCOUNTS (User Account - 1.png)
    public function accountDashboard()
    {
        // Lấy thông tin User đang đăng nhập qua Session Auth thuần
        $user = Auth::user();

        // Lấy các đơn hàng gần đây của User này để hiển thị trong mục "My Orders"
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('client.account.dashboard', compact('user', 'recentOrders'));
    }
}
