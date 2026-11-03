<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Dịch vụ gửi email giao dịch qua Brevo.
 *
 * Mặc định dùng SMTP (qua hệ thống Mail của Laravel — cấu hình sẵn MAIL_*
 * trong .env, ví dụ smtp-relay.brevo.com). Nếu BREVO_CHANNEL=api và có
 * BREVO_API_KEY, dịch vụ sẽ dùng HTTP API của Brevo.
 *
 * Cấu hình môi trường:
 * - BREVO_CHANNEL=smtp (mặc định) hoặc api
 * - BREVO_SENDER_EMAIL / BREVO_SENDER_NAME (địa chỉ người gửi)
 * - BREVO_API_KEY (chỉ cần khi dùng kênh api)
 */
class BrevoMailService
{
    private const API_ENDPOINT = 'https://api.brevo.com/v3/smtp/email';

    private int $timeoutSeconds;

    public function __construct()
    {
        // Thời gian chờ ngắn để người dùng không phải đợi khi API gặp sự cố
        $this->timeoutSeconds = (int) config('services.brevo.timeout', 10);
    }

    /**
     * Gửi email HTML qua Brevo.
     *
     * @return bool true nếu Brevo chấp nhận yêu cầu, false nếu gửi thất bại.
     */
    public function sendHtml(string $toEmail, string $toName, string $subject, string $htmlContent): bool
    {
        if (config('services.brevo.channel', 'smtp') === 'api') {
            return $this->sendViaApi($toEmail, $toName, $subject, $htmlContent);
        }

        return $this->sendViaSmtp($toEmail, $toName, $subject, $htmlContent);
    }

    /**
     * Gửi qua SMTP đã cấu hình trong .env (MAIL_MAILER=smtp, host Brevo).
     */
    private function sendViaSmtp(string $toEmail, string $toName, string $subject, string $htmlContent): bool
    {
        try {
            $fromEmail = (string) (config('services.brevo.sender_email') ?: config('mail.from.address'));
            $fromName = (string) (config('services.brevo.sender_name') ?: config('mail.from.name', config('app.name')));

            Mail::html($htmlContent, function ($message) use ($toEmail, $toName, $subject, $fromEmail, $fromName) {
                $message->to($toEmail, $toName)
                    ->subject($subject)
                    ->from($fromEmail, $fromName);
            });

            return true;
        } catch (\Throwable $e) {
            // Ghi log kỹ thuật, không làm sập luồng người dùng
            Log::error('Gửi email qua SMTP thất bại: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Gửi qua HTTP API của Brevo (dự phòng khi không dùng SMTP).
     */
    private function sendViaApi(string $toEmail, string $toName, string $subject, string $htmlContent): bool
    {
        $apiKey = (string) config('services.brevo.api_key');
        $senderEmail = (string) config('services.brevo.sender_email');
        $senderName = (string) (config('services.brevo.sender_name', config('app.name', 'Computer Store')));

        if ($apiKey === '' || $senderEmail === '') {
            // Thiếu cấu hình: ghi log kỹ thuật, không làm sập luồng người dùng
            Log::error('Brevo API chưa được cấu hình: kiểm tra BREVO_API_KEY và BREVO_SENDER_EMAIL.');

            return false;
        }

        try {
            $response = Http::withHeaders([
                'api-key' => $apiKey,
                'accept' => 'application/json',
            ])->timeout($this->timeoutSeconds)->post(self::API_ENDPOINT, [
                'sender' => [
                    'name' => $senderName,
                    'email' => $senderEmail,
                ],
                'to' => [
                    [
                        'email' => $toEmail,
                        'name' => $toName,
                    ],
                ],
                'subject' => $subject,
                'htmlContent' => $htmlContent,
            ]);

            if ($response->successful()) {
                return true;
            }

            // Lỗi từ Brevo (sai API key, người gửi chưa xác minh, v.v.)
            Log::error('Brevo trả về lỗi khi gửi email.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        } catch (ConnectionException $e) {
            // Lỗi mạng / timeout
            Log::error('Không thể kết nối đến Brevo API: ' . $e->getMessage());

            return false;
        } catch (\Throwable $e) {
            Log::error('Lỗi không xác định khi gửi email qua Brevo: ' . $e->getMessage());

            return false;
        }
    }
}
