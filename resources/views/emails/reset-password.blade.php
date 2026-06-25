<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #eef2f6;
        }
        .header {
            background-color: #0d6efd;
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.6;
        }
        .code-box {
            background-color: #f1f6ff;
            border: 2px dashed #0d6efd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .code {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 6px;
            color: #0d6efd;
            margin: 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #eef2f6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Cửa hàng máy tính - Computer Store</h1>
        </div>
        <div class="content">
            <p>Xin chào,</p>
            <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Vui lòng sử dụng mã xác nhận OTP dưới đây để hoàn tất quá trình:</p>
            
            <div class="code-box">
                <p class="code">{{ $code }}</p>
            </div>
            
            <p>Mã OTP này có hiệu lực trong vòng <strong>15 phút</strong>. Nếu bạn không yêu cầu đặt lại mật khẩu, bạn có thể bỏ qua email này.</p>
            <p>Trân trọng,<br>Đội ngũ hỗ trợ khách hàng Computer Store</p>
        </div>
        <div class="footer">
            <p>Email này được gửi tự động. Vui lòng không trả lời trực tiếp email này.</p>
        </div>
    </div>
</body>
</html>
