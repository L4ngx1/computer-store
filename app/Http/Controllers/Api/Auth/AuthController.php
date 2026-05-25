<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiController
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials)) {
            return $this->error('Tài khoản hoặc mật khẩu không chính xác.');
        }

        $request->session()->regenerate();

        return $this->success([
            'user' => Auth::user(),
        ], 'Đăng nhập thành công.');
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->success(null, 'Đăng xuất thành công.');
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'role' => 'customer',
        ]);

        Auth::login($user);

        return $this->success([
            'user' => $user,
        ], 'Đăng ký thành công.', 201);
    }

    public function me(Request $request): JsonResponse
    {
        return $this->success([
            'user' => $request->user(),
        ], 'Lấy thông tin người dùng hiện tại thành công.');
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
        ]);

        $user->update($validated);

        return $this->success([
            'user' => $user->fresh(),
        ], 'Cập nhật hồ sơ thành công.');
    }

    public function changePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (! Auth::attempt(['email' => $request->user()->email, 'password' => $validated['current_password']])) {
            return $this->error('Mật khẩu hiện tại không đúng.');
        }

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return $this->success(null, 'Đổi mật khẩu thành công.');
    }
}