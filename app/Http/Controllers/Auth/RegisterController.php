<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
        ], [
            'first_name.required' => 'Tên là bắt buộc.',
            'first_name.max' => 'Tên không được vượt quá 255 ký tự.',
            'last_name.max' => 'Họ không được vượt quá 255 ký tự.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.letters' => 'Mật khẩu phải chứa ít nhất một chữ cái.',
            'password.numbers' => 'Mật khẩu phải chứa ít nhất một chữ số.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Ghép họ và tên hiển thị (họ đứng trước theo thói quen Việt Nam)
        $name = trim($validated['first_name'] . ' ' . ($validated['last_name'] ?? ''));

        // Tạo tài khoản chưa xác minh email, sau đó gửi thư xác minh
        $user = User::create([
            'name' => $name,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        event(new Registered($user));

        // Đăng nhập sẵn nhưng bị chặn bởi middleware 'verified' cho các tính năng bảo mật
        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Đăng ký thành công! Vui lòng xác minh email để sử dụng đầy đủ tính năng.');
    }
}
