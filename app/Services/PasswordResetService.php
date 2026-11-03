<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Dịch vụ đặt lại mật khẩu bằng mã OTP 6 chữ số gửi qua Brevo.
 *
 * Nguyên tắc bảo mật:
 * - OTP được tạo bằng random_int() (an toàn mật mã), lưu dạng hash bcrypt.
 * - OTP hết hạn sau 15 phút, chỉ dùng một lần, tự vô hiệu sau khi đổi mật khẩu.
 * - Giới hạn số lần gửi (cooldown) và số lần nhập sai (khóa OTP).
 * - Thông báo trả về luôn chung chung để tránh dò tìm email tồn tại.
 */
class PasswordResetService
{
    private const OTP_TTL_MINUTES = 15;

    private const RESEND_COOLDOWN_SECONDS = 60;

    private const MAX_ATTEMPTS = 5;

    public function __construct(private BrevoMailService $mailer)
    {
    }

    /**
     * Tạo và gửi mã OTP đến email. Luôn trả về thông báo chung chung.
     *
     * @return array{ok: bool, message: string}
     */
    public function sendOtp(string $email): array
    {
        $email = strtolower(trim($email));

        // Chống lạm dụng: mỗi email chỉ được yêu cầu 1 lần mỗi phút
        if (DB::table('password_reset_tokens')->where('email', $email)->exists()) {
            $record = DB::table('password_reset_tokens')->where('email', $email)->first();
            $age = Carbon::parse($record->created_at)->diffInSeconds(now());

            if ($age < self::RESEND_COOLDOWN_SECONDS) {
                $remaining = self::RESEND_COOLDOWN_SECONDS - $age;

                return [
                    'ok' => false,
                    'message' => 'Vui lòng đợi ' . $remaining . ' giây trước khi yêu cầu mã OTP mới.',
                ];
            }
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            // Không tiết lộ email có tồn tại hay không
            return [
                'ok' => true,
                'message' => 'Nếu email của bạn tồn tại trong hệ thống, mã OTP gồm 6 chữ số đã được gửi đến email của bạn.',
            ];
        }

        $otp = $this->generateOtp();

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($otp),
                'attempts' => 0,
                'created_at' => now(),
            ]
        );

        $sent = $this->mailer->sendHtml(
            $user->email,
            $user->name,
            'Mã xác nhận OTP đặt lại mật khẩu - Computer Store',
            view('emails.otp-reset-password', [
                'otp' => $otp,
                'expiresMinutes' => self::OTP_TTL_MINUTES,
                'userName' => $user->name,
            ])->render()
        );

        if (! $sent) {
            // Không xóa bản ghi đã lưu: người dùng có thể thử lại sau khi hệ thống hồi phục
            return [
                'ok' => false,
                'message' => 'Không thể gửi email lúc này. Vui lòng thử lại sau ít phút.',
            ];
        }

        return [
            'ok' => true,
            'message' => 'Nếu email của bạn tồn tại trong hệ thống, mã OTP gồm 6 chữ số đã được gửi đến email của bạn.',
        ];
    }

    /**
     * Kiểm tra OTP người dùng nhập.
     *
     * @return array{ok: bool, message: string}
     */
    public function verifyOtp(string $email, string $otp): array
    {
        $email = strtolower(trim($email));
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (! $record) {
            return ['ok' => false, 'message' => 'Mã xác nhận OTP không đúng hoặc đã hết hạn.'];
        }

        // Khóa OTP khi nhập sai quá số lần cho phép
        if ((int) $record->attempts >= self::MAX_ATTEMPTS) {
            return ['ok' => false, 'message' => 'Bạn đã nhập sai quá nhiều lần. Vui lòng yêu cầu mã OTP mới.'];
        }

        if (Carbon::parse($record->created_at)->addMinutes(self::OTP_TTL_MINUTES)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            return ['ok' => false, 'message' => 'Mã xác nhận OTP đã hết hạn. Vui lòng yêu cầu mã mới.'];
        }

        if (! Hash::check($otp, $record->token)) {
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->increment('attempts');

            $remaining = self::MAX_ATTEMPTS - ((int) $record->attempts + 1);

            return [
                'ok' => false,
                'message' => 'Mã xác nhận OTP không đúng.' . ($remaining > 0
                    ? ' Bạn còn ' . $remaining . ' lần thử.'
                    : ' Vui lòng yêu cầu mã OTP mới.'),
            ];
        }

        return ['ok' => true, 'message' => ''];
    }

    /**
     * Đặt lại mật khẩu sau khi OTP hợp lệ. OTP bị vô hiệu sau khi thành công.
     *
     * @return array{ok: bool, message: string}
     */
    public function resetPassword(string $email, string $otp, string $newPassword): array
    {
        $verification = $this->verifyOtp($email, $otp);

        if (! $verification['ok']) {
            return $verification;
        }

        $email = strtolower(trim($email));
        $user = User::where('email', $email)->first();

        if (! $user) {
            return ['ok' => false, 'message' => 'Mã xác nhận OTP không đúng hoặc đã hết hạn.'];
        }

        DB::transaction(function () use ($user, $email, $newPassword) {
            $user->forceFill(['password' => $newPassword])->save();

            // OTP chỉ dùng một lần: xóa ngay sau khi đổi mật khẩu thành công
            DB::table('password_reset_tokens')->where('email', $email)->delete();
        });

        return ['ok' => true, 'message' => 'Mật khẩu đã đặt lại thành công! Bạn có thể đăng nhập bằng mật khẩu mới.'];
    }

    /**
     * Sinh mã OTP 6 chữ số an toàn mật mã.
     */
    private function generateOtp(): string
    {
        return (string) random_int(100000, 999999);
    }
}
