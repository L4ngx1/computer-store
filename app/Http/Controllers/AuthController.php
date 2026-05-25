<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập phía Admin
    public function showLoginForm()
    {
        return view('auth.login'); // Tự tạo file view giao diện
    }

    // Xử lý logic khi bấm nút Đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Sử dụng hàm Auth::attempt có sẵn của Laravel để check database
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Nếu là admin thì vào thẳng dashboard, ngược lại về trang chủ
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Tài khoản hoặc mật khẩu không chính xác.',
        ]);
    }

    // Xử lý Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
