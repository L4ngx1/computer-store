@extends('layouts.site')

@section('title', 'Xác minh email thành công - Computer Store')

@section('content')
    <div class="small text-secondary mb-4">Trang chủ › Xác minh email</div>

    <section class="py-5">
        <div class="container-xxl">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-check-circle-fill text-success fs-1 mb-3"></i>
                            @if ($alreadyVerified)
                                <h2 class="card-title fw-bold">Email đã được xác minh trước đó</h2>
                                <p class="text-muted mb-4">Địa chỉ email của bạn đã được xác minh. Vui lòng đăng nhập để tiếp tục.</p>
                            @else
                                <h2 class="card-title fw-bold">Xác minh email thành công!</h2>
                                <p class="text-muted mb-4">Địa chỉ email của bạn đã được xác minh. Vui lòng đăng nhập để tiếp tục sử dụng trang web.</p>
                            @endif
                            <a href="{{ route('login.form') }}" class="btn btn-primary px-4">Đăng nhập ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection