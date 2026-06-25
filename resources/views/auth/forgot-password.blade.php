@extends('layouts.site')

@section('title', 'Quên mật khẩu - Computer Store')

@section('content')
    <div class="small text-secondary mb-4">Trang chủ › Đăng nhập › Quên mật khẩu</div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <section class="bg-light p-4 rounded-3 shadow-sm">
                <h1 class="h3 fw-bold mb-2">Quên mật khẩu</h1>
                <p class="text-secondary mb-4">Nhập email đã đăng ký của bạn. Chúng tôi sẽ gửi một mã xác nhận OTP gồm 6 chữ số để bạn đặt lại mật khẩu mới.</p>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">Email tài khoản</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control form-control-lg" placeholder="example@domain.com" required autofocus>
                    </div>

                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">Gửi mã xác nhận</button>
                        <a href="{{ route('login.form') }}" class="text-secondary fw-semibold text-decoration-none"><i class="bi bi-arrow-left"></i> Quay lại Đăng nhập</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
