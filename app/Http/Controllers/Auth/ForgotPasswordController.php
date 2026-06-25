<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\ResetPasswordMail;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Display the form to request a password reset link.
     */
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a reset code to the given user.
     */
    public function sendResetCodeEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'email.exists' => 'Email này chưa được đăng ký trong hệ thống.',
        ]);

        $code = (string) mt_rand(100000, 999999);

        // Save or update the code in the password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $code,
                'created_at' => Carbon::now(),
            ]
        );

        // Send email
        Mail::to($request->email)->send(new ResetPasswordMail($code));

        return redirect()->route('password.reset', ['email' => $request->email])
            ->with('success', 'Mã xác nhận OTP gồm 6 chữ số đã được gửi đến email của bạn.');
    }

    /**
     * Display the password reset view for the given token/email.
     */
    public function showResetForm(Request $request): View
    {
        $email = $request->query('email');
        return view('auth.reset-password', compact('email'));
    }

    /**
     * Reset the given user's password.
     */
    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'code' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'email.exists' => 'Email không tồn tại.',
            'code.required' => 'Vui lòng nhập mã xác nhận.',
            'code.size' => 'Mã xác nhận phải gồm đúng 6 chữ số.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (! $record || $record->token !== $request->code) {
            return back()->withErrors(['code' => 'Mã xác nhận OTP không đúng hoặc đã hết hạn.'])
                ->withInput();
        }

        // Check if token expired (valid for 15 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return back()->withErrors(['code' => 'Mã xác nhận OTP đã hết hạn. Vui lòng yêu cầu mã mới.'])
                ->withInput();
        }

        // Reset password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token record
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login.form')
            ->with('success', 'Mật khẩu đã đặt lại thành công! Bạn có thể đăng nhập bằng mật khẩu mới.');
    }
}
