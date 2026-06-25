@extends('layouts.site')

@section('title', 'Thanh Toán')

@section('content')

{{-- CSS Tùy chỉnh đồng bộ với trang Giỏ hàng --}}
<style>
    .btn-gradient {
        background: linear-gradient(90deg, #0d6efd 0%, #198754 100%);
        color: white;
        border: none;
        transition: 0.3s;
    }
    .btn-gradient:hover {
        background: linear-gradient(90deg, #198754 0%, #0d6efd 100%);
        color: white;
        transform: translateY(-2px);
    }
    .btn-modern {
        border-radius: 8px;
        padding: 12px 20px;
        font-weight: 600;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .card { border-radius: 12px; }
</style>

<div class="container py-5">

    <div class="mb-5 text-center">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-credit-card me-2"></i> Thanh Toán Đơn Hàng
        </h2>
        <p class="text-muted">Vui lòng kiểm tra thông tin kỹ lưỡng trước khi hoàn tất đặt hàng.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('client.checkout.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            {{-- CỘT BÊN TRÁI: FORM THÔNG TIN --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4 text-secondary">Thông tin khách hàng</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label>Họ *</label><input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required></div>
                            <div class="col-md-6 mb-3"><label>Tên *</label><input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required></div>
                            <div class="col-md-6 mb-3"><label>Email *</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
                            <div class="col-md-6 mb-3"><label>Số điện thoại *</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required></div>
                            <div class="col-md-6 mb-3"><label>Công ty</label><input type="text" name="company" class="form-control" value="{{ old('company') }}"></div>
                            <div class="col-md-6 mb-3"><label>Mã ZIP *</label><input type="text" name="zip" class="form-control" value="{{ old('zip') }}" required></div>
                            <div class="col-12 mb-3"><label>Địa chỉ *</label><textarea name="address" class="form-control" required>{{ old('address') }}</textarea></div>
                            <div class="col-12 mb-3"><label>Thành phố *</label><input type="text" name="city" class="form-control" value="{{ old('city') }}" required></div>
                        </div>
                    </div>
                </div>

                {{-- SHIPPING --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3 text-secondary">Phương thức giao hàng</h4>
                        <div class="form-check border p-3 mb-2 rounded shadow-sm">
                            <input class="form-check-input" type="radio" name="shipping" value="standard" checked>
                            <label class="form-check-label fw-bold"> Giao hàng tiêu chuẩn</label>
                        </div>
                        <div class="form-check border p-3 rounded shadow-sm">
                            <input class="form-check-input" type="radio" name="shipping" value="pickup">
                            <label class="form-check-label fw-bold"> Nhận tại cửa hàng</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CỘT BÊN PHẢI: TỔNG ĐƠN HÀNG --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-lg sticky-top" style="top:20px;">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Đơn hàng của bạn</h4>

                        @php $total = 0; @endphp
                        @forelse($cartItems as $item)
                            @php
                                $qty = $item->quantity;
                                $price = $item->product->sale_price ?? $item->product->price;
                                $subtotal = $price * $qty;
                                $total += $subtotal;
                            @endphp
                            <div class="d-flex mb-3 align-items-center">
                                <img src="{{ asset($item->product->thumbnail) }}" width="60" height="60" class="rounded border me-3" style="object-fit:cover;">
                                <div>
                                    <div class="fw-semibold small">{{ $item->product->name }}</div>
                                    <div class="text-danger fw-bold">{{ number_format($subtotal, 0, ',', '.') }}₫</div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">Giỏ hàng trống</p>
                        @endforelse

                        <hr>
                        <div class="d-flex justify-content-between h5 mb-4">
                            <strong>Tổng cộng</strong>
                            <strong class="text-primary">{{ number_format($total, 0, ',', '.') }}₫</strong>
                        </div>

                        {{-- CÁC NÚT BẤM ĐÃ THIẾT KẾ LẠI --}}
                        <button type="submit" class="btn btn-gradient btn-modern w-100 btn-lg mb-2">
                            <i class="fas fa-check-circle me-2"></i> Xác nhận đặt hàng
                        </button>

                        <a href="{{ route('client.cart') }}" class="btn btn-outline-secondary btn-modern w-100">
                            <i class="fas fa-arrow-left me-2"></i> Quay lại giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection