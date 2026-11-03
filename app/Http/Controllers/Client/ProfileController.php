<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load(['orders' => function ($query) {
            $query->with('items')->orderBy('created_at', 'desc');
        }]);

        return view('client.account', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user) {
            abort(401);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'name.max' => 'Họ tên không được vượt quá 255 ký tự.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
        ]);

        // Đổi email hoặc mật khẩu đều yêu cầu xác nhận mật khẩu hiện tại
        $emailChanged = $validated['email'] !== $user->email;
        $passwordChanged = $request->filled('password');

        if ($emailChanged || $passwordChanged) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
            ], [
                'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại để xác nhận thay đổi.',
                'current_password.current_password' => 'Mật khẩu hiện tại không chính xác.',
            ]);
        }

        if ($passwordChanged) {
            $request->validate([
                'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
            ], [
                'password.required' => 'Vui lòng nhập mật khẩu mới.',
                'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
                'password.letters' => 'Mật khẩu phải chứa ít nhất một chữ cái.',
                'password.numbers' => 'Mật khẩu phải chứa ít nhất một chữ số.',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            ]);

            $validated['password'] = Hash::make($request->input('password'));
        }

        $user->update($validated);

        // Đổi email requires re-verification: reset and resend
        if ($emailChanged) {
            $user->forceFill(['email_verified_at' => null])->save();
            $user->sendEmailVerificationNotification();

            return back()->with('success', 'Cập nhật thông tin thành công. Email mới cần được xác minh — vui lòng kiểm tra hộp thư của bạn.');
        }

        return back()->with('success', 'Cập nhật thông tin thành công.');
    }
}
