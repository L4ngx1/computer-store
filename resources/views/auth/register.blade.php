<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng kí - Computer Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-blue: #0066ff;
            --light-gray: #f8f9fa;
            --border-gray: #e9ecef;
        }
        
        body {
            background-color: var(--light-gray);
        }
        
        .register-container {
            background: white;
            padding: 60px 40px;
            border-radius: 8px;
            margin: 60px 0;
        }
        
        .register-container h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #212529;
        }
        
        .register-container .subtitle {
            font-size: 15px;
            color: #666;
            margin-bottom: 40px;
        }
        
        .form-control {
            border: 1px solid var(--border-gray);
            padding: 12px 15px;
            font-size: 14px;
            border-radius: 4px;
        }
        
        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 102, 255, 0.15);
        }
        
        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: #212529;
            margin-bottom: 8px;
        }
        
        .btn-register {
            background-color: var(--primary-blue);
            border: none;
            padding: 12px 40px;
            font-weight: 600;
            border-radius: 4px;
            color: white;
            margin-top: 20px;
        }
        
        .btn-register:hover {
            background-color: #0052cc;
            color: white;
        }
        
        .login-link {
            font-size: 14px;
            color: #666;
            margin-top: 20px;
        }
        
        .login-link a {
            color: var(--primary-blue);
            text-decoration: none;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .newsletter-section {
            background-color: #1a1a1a;
            color: white;
            padding: 50px 40px;
            margin-top: 60px;
            border-radius: 8px;
        }
        
        .newsletter-section h4 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .newsletter-section p {
            font-size: 14px;
            margin-bottom: 20px;
            color: #ccc;
        }
        
        .newsletter-form {
            display: flex;
            gap: 10px;
            margin-bottom: 40px;
        }
        
        .newsletter-form input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #444;
            border-radius: 4px;
            background-color: #2a2a2a;
            color: white;
            font-size: 14px;
        }
        
        .newsletter-form input::placeholder {
            color: #999;
        }
        
        .newsletter-form button {
            padding: 12px 30px;
            background-color: var(--primary-blue);
            border: none;
            border-radius: 4px;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }
        
        .newsletter-form button:hover {
            background-color: #0052cc;
        }
        
        .newsletter-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #444;
        }
        
        .newsletter-column h5 {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .newsletter-column ul {
            list-style: none;
            padding: 0;
        }
        
        .newsletter-column li {
            margin-bottom: 10px;
        }
        
        .newsletter-column a {
            color: #ccc;
            text-decoration: none;
            font-size: 13px;
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
        }
        
        .social-icons {
            display: flex;
            gap: 15px;
        }
        
        .social-icons a {
            color: #ccc;
            font-size: 16px;
            text-decoration: none;
        }
        
        .social-icons a:hover {
            color: var(--primary-blue);
        }
    </style>
</head>
<body>
    <!-- Register Section -->
    <div class="container">
        <div class="register-container">
            <h1>Tạo Tài Khoản Mới</h1>
            <p class="subtitle">Vui lòng điền các thông tin dưới đây để tạo tài khoản</p>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                <div class="form-row">
                    <div>
                        <label for="first_name" class="form-label">Tên</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="form-control" required>
                    </div>
                    <div>
                        <label for="last_name" class="form-label">Họ</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="form-control">
                    </div>
                </div>

                <div class="mb-3" style="margin-top: 20px;">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Số điện thoại</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="form-control">
                </div>

                <div class="form-row">
                    <div>
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div>
                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-register">Tạo Tài Khoản</button>
                <div class="login-link">
                    Bạn đã có tài khoản? <a href="{{ route('login.form') }}">Đăng nhập tại đây</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Newsletter Section -->
    <div class="container">
        <div class="newsletter-section">
            <h4>Đăng kí Nhận Bản Tin Của Chúng Tôi</h4>
            <p>Hãy là người đầu tiên nhận được thông tin về các sản phẩm mới hôm nay</p>
            
            <form class="newsletter-form">
                <input type="email" placeholder="Địa chỉ email của bạn" required>
                <button type="submit">Đăng kí</button>
            </form>

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
