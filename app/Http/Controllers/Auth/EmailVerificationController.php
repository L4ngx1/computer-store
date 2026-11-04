<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    /**
     * Trang thông báo: hiển thị khi tài khoản chưa xác minh email.
     */
    public function notice(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user && $user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        return view('auth.verify-email');
    }

    /**
     * Xác minh liên kết đã ký từ email.
     */
    public function verify(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login.form')
                ->with('error', 'Vui lòng đăng nhập để xác minh email.');
        }

        if (! hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            throw new AuthorizationException();
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException();
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home')->with('success', 'Email của bạn đã được xác minh trước đó.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('home')->with('success', 'Xác minh email thành công! Cảm ơn bạn đã xác minh địa chỉ email.');
    }

    /**
     * Gửi lại thư xác minh (có giới hạn tần suất).
     */
    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login.form');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        // Chống spam email: tối đa 2 lần gửi mỗi phút cho mỗi người dùng
        if (RateLimiter::tooManyAttempts('verify-email:' . $user->id, 2)) {
            $seconds = RateLimiter::availableIn('verify-email:' . $user->id);

            return back()->with('error', 'Bạn đã yêu cầu quá nhiều lần. Vui lòng thử lại sau ' . (int) $seconds . ' giây.');
        }

        RateLimiter::hit('verify-email:' . $user->id, 60);

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Đã gửi lại liên kết xác minh mới đến email của bạn.');
    }
}
