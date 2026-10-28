<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = \App\Models\User::where('email', $credentials['email'])->first();
        if ($user?->is_locked) {
            return back()->withErrors(['email' => 'Tài khoản đã bị khóa.'])->onlyInput('email');
        }

        if (! Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email hoặc mật khẩu không chính xác.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        if (Auth::user()->role !== 'admin') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Tài khoản này không có quyền truy cập admin.',
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
            'description' => 'Đăng nhập khu vực quản trị',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        $user->api_token = \Illuminate\Support\Str::random(60);
        $user->save();

        return redirect()->route('admin.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}