@extends('layouts.site')

@section('title', 'Thanh Toán')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="h2 fw-bold">Thanh Toán</h1>
        <p class="text-muted">Vui lòng kiểm tra thông tin kỹ lưỡng trước khi hoàn tất đặt hàng.</p>
    </div>

    <form action="{{ route('client.checkout.store') }}" method="POST">
        @csrf
        <div class="row g-5">
            <!-- Cột thông tin giao hàng -->
            <div class="col-lg-8">
                <h2 class="h5 fw-bold mb-4">Thông tin giao hàng</h2>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">Họ *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', Auth::user()->name ? explode(' ', Auth::user()->name)[0] : '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Tên *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', Auth::user()->name ? substr(strstr(Auth::user()->name, ' '), 1) : '') }}" required>
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            </div>
                            <div class="col-12">
                                <label for="phone" class="form-label">Số điện thoại *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}" required>
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label">Địa chỉ *</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Số nhà, tên đường, phường/xã" value="{{ old('address', Auth::user()->address) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="form-label">Thành phố / Tỉnh *</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="zip" class="form-label">Mã bưu điện (ZIP) *</label>
                                <input type="text" class="form-control" id="zip" name="zip" value="{{ old('zip') }}" required>
                            </div>
                             <div class="col-12">
                                <label for="company" class="form-label">Công ty (Tùy chọn)</label>
                                <input type="text" class="form-control" id="company" name="company" value="{{ old('company') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="h5 fw-bold mb-4 mt-5">Phương thức vận chuyển</h2>
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-2">
                        <div class="list-group list-group-flush">
                            <label class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <input class="form-check-input me-2" type="radio" name="shipping" value="standard" checked>
                                    <span class="fw-bold">Giao hàng tiêu chuẩn</span>
                                    <small class="d-block text-muted">Giao hàng trong 2-5 ngày làm việc</small>
                                </div>
                                <span class="fw-bold">Miễn phí</span>
                            </label>
                            <label class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <input class="form-check-input me-2" type="radio" name="shipping" value="pickup">
                                    <span class="fw-bold">Nhận tại cửa hàng</span>
                                    <small class="d-block text-muted">Nhận hàng tại 175 Tây Sơn, Đống Đa, Hà Nội</small>
                                </div>
                                <span class="fw-bold">Miễn phí</span>
                            </label>
                        </div>
                    </div>
                </div>

                 <h2 class="h5 fw-bold mb-4 mt-5">Phương thức thanh toán</h2>
                 <div class="card border-0 shadow-sm rounded-4">
                     <div class="card-body p-2">
                         <div class="list-group list-group-flush">
                             <label class="list-group-item list-group-item-action d-flex align-items-center">
                                 <input class="form-check-input me-3" type="radio" name="payment_method" value="cod" checked>
                                 <i class="bi bi-cash-coin fs-4 me-3 text-primary"></i>
                                 <div>
                                     <span class="fw-bold">Thanh toán khi nhận hàng (COD)</span>
                                     <small class="d-block text-muted">Thanh toán bằng tiền mặt khi shipper giao hàng.</small>
                                 </div>
                             </label>
                         </div>
                     </div>
                 </div>
            </div>

            <!-- Cột tóm tắt đơn hàng -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 2rem;">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-4">Tóm tắt đơn hàng</h2>
                        <div class="list-group list-group-flush mb-3">
                        @forelse($cartItems as $item)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $item->product->thumbnail ? Storage::url($item->product->thumbnail) : 'https://via.placeholder.com/60' }}" alt="{{ $item->product->name }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: contain;">
                                    <div>
                                        <div class="fw-bold small text-truncate" style="max-width: 150px;">{{ $item->product->name }}</div>
                                        <div class="small text-muted">Số lượng: {{ $item->quantity }}</div>
                                    </div>
                                </div>
                                <span class="fw-bold small">{{ number_format(($item->product->sale_price ?? $item->product->price) * $item->quantity, 0, ',', '.') }}đ</span>
                            </div>
                        @empty
                            <p class="text-muted text-center">Giỏ hàng của bạn đang trống.</p>
                        @endforelse
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính</span>
                            <span class="fw-bold">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Phí vận chuyển</span>
                            <span class="fw-bold">Miễn phí</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                            <span>Tổng cộng</span>
                            <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold" {{ $cartItems->isEmpty() ? 'disabled' : '' }}>
                                <i class="bi bi-bag-check-fill me-2"></i> Hoàn tất đặt hàng
                            </button>
                            <a href="{{ route('client.cart') }}" class="btn btn-outline-secondary rounded-pill">
                                <i class="bi bi-arrow-left me-1"></i> Quay lại giỏ hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection