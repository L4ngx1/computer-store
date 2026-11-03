<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Client\CartController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\View\View;

class ClientAuthController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        // Chống brute-force: giới hạn 5 lần thử mỗi phút theo cặp email + IP
        $throttleKey = strtolower($credentials['email']) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => 'Bạn đã thử đăng nhập quá nhiều lần. Vui lòng thử lại sau ' . $seconds . ' giây.',
            ]);
        }

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Auth::validate($credentials)) {
            RateLimiter::hit($throttleKey);

            return back()->withErrors([
                'email' => 'Email hoặc mật khẩu không chính xác.',
            ])->onlyInput('email');
        }

        if ($user->is_locked) {
            return back()->withErrors(['email' => 'Tài khoản đã bị khóa.'])->onlyInput('email');
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey);

            return back()->withErrors([
                'email' => 'Email hoặc mật khẩu không chính xác.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        RateLimiter::clear($throttleKey);

        if (Auth::user()->role === 'admin') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Tài khoản quản trị phải đăng nhập tại /admin/login.',
            ])->onlyInput('email');
        }

        $user = Auth::user();
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);
        AuditLog::create([
            'user_id' => $user->id,
            'actor_id' => $user->id,
            'action' => 'login',
            'description' => 'Đăng nhập tài khoản khách hàng',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Hợp nhất giỏ hàng của khách (session) vào tài khoản sau khi đăng nhập
        CartController::mergeSessionCart(Auth::id());

        // Chuyển người dùng chưa xác minh đến trang thông báo xác minh
        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return redirect()->intended(route('home'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
