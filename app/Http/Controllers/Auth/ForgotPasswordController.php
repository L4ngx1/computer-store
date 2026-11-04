<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\PasswordResetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    private const OTP_TTL_MINUTES = 15;

    public function __construct(private PasswordResetService $resetService)
    {
    }

    /**
     * Hiển thị form yêu cầu đặt lại mật khẩu.
     */
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Gửi mã OTP đến email (qua Brevo).
     */
    public function sendResetCodeEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Định dạng email không hợp lệ.',
        ]);

        // Chống spam: giới hạn 5 yêu cầu mỗi phút theo IP
        $throttleKey = 'password-email:' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->with('error', 'Bạn đã yêu cầu quá nhiều lần. Vui lòng thử lại sau ' . (int) $seconds . ' giây.');
        }
        RateLimiter::hit($throttleKey, 60);

        $result = $this->resetService->sendOtp($request->input('email'));

        if (! $result['ok']) {
            return back()->with('error', $result['message'])->onlyInput('email');
        }

        return redirect()->route('password.reset', ['email' => $request->input('email')])
            ->with('success', $result['message']);
    }

    /**
     * Hiển thị form nhập OTP và mật khẩu mới.
     */
    public function showResetForm(Request $request): View
    {
        $email = $request->query('email');
        return view('auth.reset-password', compact('email'));
    }

    /**
     * Xác nhận OTP và đặt lại mật khẩu.
     */
    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'code.required' => 'Vui lòng nhập mã xác nhận.',
            'code.size' => 'Mã xác nhận phải gồm đúng 6 chữ số.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'password.letters' => 'Mật khẩu phải chứa ít nhất một chữ cái.',
            'password.numbers' => 'Mật khẩu phải chứa ít nhất một chữ số.',
            'password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $result = $this->resetService->resetPassword(
            $request->input('email'),
            $request->input('code'),
            $request->input('password')
        );

        if (! $result['ok']) {
            return back()
                ->withErrors(['code' => $result['message']])
                ->withInput($request->only('email'));
        }

        return redirect()->route('login.form')->with('success', $result['message']);
    }
}
