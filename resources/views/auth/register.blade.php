@extends('layouts.site')

@section('title', 'Đăng kí - Computer Store')

@section('content')
    <div class="small text-secondary mb-4">Trang chủ › Đăng kí</div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <section class="bg-light p-4">
                <h1 class="h2 fw-bold mb-2">Tạo Tài Khoản Mới</h1>
                <p class="text-secondary mb-4">Vui lòng điền các thông tin dưới đây để tạo tài khoản.</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register.store') }}" class="row g-4">
                    @csrf

                    <div class="col-md-6">
                        <label for="first_name" class="form-label fw-semibold">Tên</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="form-control form-control-lg" required>
                    </div>

                    <div class="col-md-6">
                        <label for="last_name" class="form-label fw-semibold">Họ</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="form-control form-control-lg">
                    </div>

                    <div class="col-12">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control form-control-lg" required>
                    </div>

                    <div class="col-12">
                        <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="form-control form-control-lg">
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                        <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-semibold">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lg" required>
                    </div>

                    <div class="col-12 d-flex flex-wrap align-items-center gap-3 mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">Tạo Tài Khoản</button>
                        <span class="text-secondary">Bạn đã có tài khoản? <a href="{{ route('login.form') }}" class="text-primary fw-semibold text-decoration-none">Đăng nhập tại đây</a></span>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
