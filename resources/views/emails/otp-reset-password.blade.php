<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã xác nhận OTP đặt lại mật khẩu</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333333;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f6f9; padding: 32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #eef2f6;">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #0d6efd; padding: 28px 30px; text-align: center;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: 700; color: #ffffff;">🖥️ Computer Store</h1>
                        </td>
                    </tr>

                    <!-- Nội dung -->
                    <tr>
                        <td style="padding: 36px 32px; line-height: 1.7;">
                            <p style="margin: 0 0 16px;">Xin chào {{ $userName }},</p>
                            <p style="margin: 0 0 20px;">Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Vui lòng sử dụng mã xác nhận OTP dưới đây để tiếp tục:</p>

                            <!-- Hộp mã OTP -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="background-color: #f1f6ff; border: 2px dashed #0d6efd; border-radius: 8px; padding: 20px; margin: 24px 0;">
                                        <span style="font-size: 32px; font-weight: 800; letter-spacing: 8px; color: #0d6efd;">{{ $otp }}</span>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 20px 0 8px;">⏱️ Mã OTP này có hiệu lực trong vòng <strong>{{ $expiresMinutes }} phút</strong>.</p>
                            <ul style="margin: 0 0 20px; padding-left: 20px; color: #555555;">
                                <li>Không chia sẻ mã OTP với bất kỳ ai — kể cả nhân viên của chúng tôi.</li>
                                <li>Chỉ sử dụng mã này trên trang web chính thức của Computer Store.</li>
                            </ul>

                            <p style="margin: 0; padding: 14px 16px; background-color: #fff8e6; border-left: 4px solid #f0ad4e; border-radius: 4px; color: #7a5b00; font-size: 14px;">
                                ⚠️ Nếu bạn <strong>không yêu cầu</strong> đặt lại mật khẩu, hãy bỏ qua email này. Mật khẩu hiện tại của bạn vẫn an toàn và không bị thay đổi.
                            </p>

                            <p style="margin: 28px 0 0;">Trân trọng,<br><strong>Đội ngũ hỗ trợ khách hàng Computer Store</strong></p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 18px 30px; text-align: center; font-size: 12px; color: #6c757d; border-top: 1px solid #eef2f6;">
                            Email này được gửi tự động. Vui lòng không trả lời trực tiếp email này.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
