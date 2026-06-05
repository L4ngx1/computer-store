@extends('layouts.site')

@section('title', 'Đăng nhập & Đăng kí - Computer Store')

@section('content')
    <div class="small text-secondary mb-4">Trang chủ › Đăng nhập</div>

    <div class="row g-4 align-items-stretch">
        <div class="col-lg-6">
            <section class="bg-light h-100 p-4">
                <h1 class="h3 fw-bold mb-2">Khách hàng đã đăng kí</h1>
                <p class="text-secondary mb-4">Nếu bạn đã có tài khoản, hãy đăng nhập bằng địa chỉ email của bạn.</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control form-control-lg" required autofocus>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                        <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">Đăng nhập</button>
                        <a href="#" class="text-primary fw-semibold text-decoration-none">Quên mật khẩu?</a>
                    </div>
                </form>
            </section>
        </div>

        <div class="col-lg-6">
            <section class="bg-light h-100 p-4">
                <h2 class="h3 fw-bold mb-2">Khách hàng mới?</h2>
                <p class="text-secondary mb-4">Tạo một tài khoản có rất nhiều lợi ích.</p>

                <ul class="list-unstyled d-grid gap-3 mb-4">
                    <li><i class="bi bi-check-circle-fill text-primary me-2"></i>Theo dõi đơn hàng của bạn</li>
                    <li><i class="bi bi-check-circle-fill text-primary me-2"></i>Lưu mục ưa thích của bạn</li>
                    <li><i class="bi bi-check-circle-fill text-primary me-2"></i>Lịch sử đơn hàng và hơn thế nữa</li>
                </ul>

                <a href="{{ route('register.form') }}" class="btn btn-primary rounded-pill px-4 py-2">Tạo Tài Khoản</a>
            </section>
        </div>
    </div>
@endsection
