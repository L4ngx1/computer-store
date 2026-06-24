@extends('layouts.site')

@section('title', 'Đặt lại mật khẩu - Computer Store')

@section('content')
    <div class="small text-secondary mb-4">Trang chủ › Đăng nhập › Quên mật khẩu › Đặt lại mật khẩu</div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <section class="bg-light p-4 rounded-3 shadow-sm">
                <h1 class="h3 fw-bold mb-2">Đặt lại mật khẩu mới</h1>
                <p class="text-secondary mb-4">Vui lòng kiểm tra email của bạn để lấy mã xác nhận OTP gồm 6 chữ số và nhập thông tin dưới đây.</p>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email tài khoản</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $email) }}" class="form-control form-control-lg" required readonly>
                    </div>

                    <div class="mb-3">
                        <label for="code" class="form-label fw-semibold">Mã xác nhận OTP (6 chữ số)</label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}" class="form-control form-control-lg text-center fw-bold" placeholder="123456" maxlength="6" pattern="[0-9]{6}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Mật khẩu mới (tối thiểu 8 ký tự)</label>
                        <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold">Xác nhận mật khẩu mới</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lg" required>
                    </div>

                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">Xác nhận đặt lại mật khẩu</button>
                        <a href="{{ route('password.request') }}" class="text-secondary fw-semibold text-decoration-none"><i class="bi bi-arrow-clockwise"></i> Gửi lại mã OTP khác</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
