<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập & Đăng kí - Computer Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-blue: #0066ff;
            --light-gray: #f8f9fa;
            --border-gray: #e9ecef;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: var(--light-gray);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
        }
        
        .login-wrapper {
            width: 100%;
            padding: 60px 20px;
        }
        
        .login-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: stretch;
        }
        
        .login-box {
            background: white;
            padding: 50px 40px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .login-box h2 {
            font-size: 22px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 8px;
        }
        
        .login-box .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 8px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 14px;
            font-size: 14px;
            border: 1px solid var(--border-gray);
            border-radius: 4px;
            transition: border-color 0.2s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.1);
        }
        
        .login-actions {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 15px;
        }
        
        .btn-signin {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            padding: 12px 28px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
            display: inline-block;
        }
        
        .btn-signin:hover {
            background-color: #0052cc;
        }
        
        .forgot-password {
            font-size: 13px;
            color: var(--primary-blue);
            text-decoration: none;
            display: inline-block;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .register-benefits {
            list-style: none;
            margin-bottom: 30px;
        }
        
        .register-benefits li {
            font-size: 14px;
            color: #666;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        
        .register-benefits i {
            color: var(--primary-blue);
            margin-right: 10px;
            font-size: 16px;
        }
        
        .btn-register {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
            width: 100%;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-register:hover {
            background-color: #0052cc;
            color: white;
            text-decoration: none;
        }
        
        .features-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin: 60px 0;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 20px;
        }
        
        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .feature-icon {
            font-size: 48px;
            color: var(--primary-blue);
            margin-bottom: 15px;
        }
        
        .feature-title {
            font-size: 16px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 10px;
        }
        
        .feature-description {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
        }
        
        .newsletter-section {
            background-color: #1a1a1a;
            color: white;
            padding: 40px 20px;
            margin-top: 60px;
        }
        
        .newsletter-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .newsletter-section h4 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        
        .newsletter-section p {
            font-size: 13px;
            color: #999;
            margin-bottom: 0;
        }
        
        .newsletter-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .newsletter-text {
            flex: 1;
        }
        
        .newsletter-form {
            display: flex;
            gap: 8px;
            align-items: center;
            flex: 0 0 auto;
        }
        
        .newsletter-form input {
            padding: 10px 14px;
            border: 1px solid #444;
            border-radius: 4px;
            background-color: transparent;
            color: white;
            font-size: 13px;
            min-height: 42px;
            min-width: 300px;
        }
        
        .newsletter-form input::placeholder {
            color: #666;
        }
        
        .newsletter-form input:focus {
            outline: none;
            border-color: var(--primary-blue);
            background-color: rgba(0, 102, 255, 0.05);
        }
        
        .newsletter-form button {
            padding: 10px 24px;
            background-color: var(--primary-blue);
            border: none;
            border-radius: 4px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
            white-space: nowrap;
            min-height: 42px;
            transition: background-color 0.2s;
        }
        
        .newsletter-form button:hover {
            background-color: #0052cc;
        }
        
        .newsletter-content {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin-top: 40px;
            padding-top: 40px;
            border-top: 1px solid #333;
        }
        
        @media (max-width: 768px) {
            .newsletter-content {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 480px) {
            .newsletter-content {
                grid-template-columns: 1fr;
            }
        }
        
        .newsletter-column h5 {
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .newsletter-column ul {
            list-style: none;
        }
        
        .newsletter-column li {
            margin-bottom: 10px;
        }
        
        .newsletter-column a {
            color: #ccc;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s;
        }
        
        .newsletter-column a:hover {
            color: var(--primary-blue);
        }
        
        .newsletter-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #444;
            font-size: 13px;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .social-icons {
            display: flex;
            gap: 15px;
        }
        
        .social-icons a {
            color: #ccc;
            font-size: 16px;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .social-icons a:hover {
            color: var(--primary-blue);
        }
    </style>
</head>
<body>
    <!-- Login & Register Section -->
    <div class="login-wrapper">
        <div class="login-content">
            <!-- Login Form -->
            <div class="login-box">
                <h2>Khách hàng đã đăng kí</h2>
                <p class="subtitle">Nếu bạn đã có tài khoản, hãy đăng nhập bằng địa chỉ email của bạn.</p>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" name="password" id="password" required>
                    </div>

                    <div class="login-actions">
                        <button type="submit" class="btn-signin">Đăng nhập</button>
                        <a href="#" class="forgot-password">Quên mật khẩu?</a>
                    </div>
                </form>
            </div>

            <!-- Register CTA -->
            <div class="login-box">
                <h2 style="font-size: 20px;">Khách hàng mới?</h2>
                <p class="subtitle">Tạo một tài khoản có rất nhiều lợi ích</p>
                
                <ul class="register-benefits">
                    <li><i class="bi bi-check-circle-fill"></i>Theo dõi đơn hàng của bạn</li>
                    <li><i class="bi bi-check-circle-fill"></i>Lưu mục ưa thích của bạn</li>
                    <li><i class="bi bi-check-circle-fill"></i>Lịch sử đơn hàng và hơn thế nữa</li>
                </ul>
                
                <a href="{{ route('register.form') }}" class="btn-register">Tạo Tài Khoản</a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features-row">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="bi bi-headset"></i>
            </div>
            <div class="feature-title">Hỗ trợ sản phẩm</div>
            <div class="feature-description">Lên tới 5 năm bảo hành miễn phí cho các sản phẩm của bạn</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <i class="bi bi-person-check"></i>
            </div>
            <div class="feature-title">Tài khoản cá nhân</div>
            <div class="feature-description">Với mục ưa thích, giao hàng nhanh và chuyên gia hỗ trợ chuyên dụng</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <i class="bi bi-percent"></i>
            </div>
            <div class="feature-title">Tiết kiệm tuyệt vời</div>
            <div class="feature-description">Lên tới 35% off các sản phẩm, bạn sẽ được hưởng giá tốt nhất</div>
        </div>
    </div>

    <!-- Newsletter Section -->
    <div class="newsletter-section">
        <div class="newsletter-inner">
            <div class="newsletter-header">
                <div class="newsletter-text">
                    <h4>Đăng kí Nhận Bản Tin Của Chúng Tôi</h4>
                    <p>Hãy là người đầu tiên nhận được thông tin về các sản phẩm mới hôm nay</p>
                </div>
                <form class="newsletter-form">
                    <input type="email" placeholder="Địa chỉ email của bạn" required>
                    <button type="submit">Đăng kí</button>
                </form>
            </div>

            <div class="newsletter-content">
                <div class="newsletter-column">
                    <h5>Thông tin</h5>
                    <ul>
                        <li><a href="#">Về chúng tôi</a></li>
                        <li><a href="#">Điều khoản sử dụng</a></li>
                        <li><a href="#">Chính sách bảo mật</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="newsletter-column">
                    <h5>PC Parts</h5>
                    <ul>
                        <li><a href="#">Bộ xử lý</a></li>
                        <li><a href="#">Bộ nhớ</a></li>
                        <li><a href="#">Ổ cứng</a></li>
                        <li><a href="#">Nguồn điện</a></li>
                    </ul>
                </div>
                <div class="newsletter-column">
                    <h5>Danh mục</h5>
                    <ul>
                        <li><a href="#">Desktop PCs</a></li>
                        <li><a href="#">Laptops</a></li>
                        <li><a href="#">Phụ kiện</a></li>
                        <li><a href="#">Các sản phẩm khác</a></li>
                    </ul>
                </div>
                <div class="newsletter-column">
                    <h5>Hỗ trợ</h5>
                    <ul>
                        <li><a href="#">Đáp ứng nhu cầu</a></li>
                        <li><a href="#">Câu hỏi thường gặp</a></li>
                        <li><a href="#">Gửi phản hồi</a></li>
                        <li><a href="#">Chính sách bảo hành</a></li>
                    </ul>
                </div>
            </div>

            <div class="newsletter-bottom">
                <div>Copyright © 2026 Shop By Inc.</div>
                <div class="social-icons">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
