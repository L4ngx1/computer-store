@extends('layouts.site')

@section('title', 'Xác minh email - Computer Store')

@section('content')
    <div class="small text-secondary mb-4">Trang chủ › Xác minh email</div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <section class="bg-light p-4 p-md-5 rounded-4 shadow-sm text-center">
                <div class="mb-3">
                    <i class="bi bi-envelope-check display-3 text-primary" aria-hidden="true"></i>
                </div>

                <h1 class="h3 fw-bold mb-2">Xác minh địa chỉ email</h1>
                <p class="text-secondary mb-4">
                    Cảm ơn bạn đã đăng ký! Vui lòng kiểm tra hộp thư của
                    <strong>{{ Auth::user()?->email }}</strong>
                    và nhấn vào liên kết xác minh chúng tôi đã gửi.
                </p>

                @if (session('success'))
                    <div class="alert alert-success" role="status">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                @endif

                <div class="d-grid gap-3">
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 w-100">
                            <i class="bi bi-arrow-clockwise me-1"></i> Gửi lại email xác minh
                        </button>
                    </form>

                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-secondary text-decoration-none">
                        Đăng xuất
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>

                <p class="text-secondary small mt-4 mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Không nhận được email? Kiểm tra thư mục Spam hoặc yêu cầu gửi lại sau ít phút.
                </p>
            </section>
        </div>
    </div>
@endsection
