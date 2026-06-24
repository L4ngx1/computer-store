@extends('layouts.site')

@section('title', 'Thanh Toán')

@section('content')
<div class="container py-5">

    <div class="mb-5">
        <h2 class="fw-bold">
            <i class="fas fa-credit-card me-2 text-primary"></i>
            Thanh Toán Đơn Hàng
        </h2>
        <p class="text-muted">
            Vui lòng kiểm tra thông tin trước khi đặt hàng.
        </p>
    </div>

    {{-- ERRORS --}}
    @if($errors->any())
        <div class="alert alert-danger">
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

            {{-- LEFT --}}
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-3">Thông tin khách hàng</h4>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Họ *</label>
                                <input type="text" name="first_name"
                                       class="form-control"
                                       value="{{ old('first_name') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Tên *</label>
                                <input type="text" name="last_name"
                                       class="form-control"
                                       value="{{ old('last_name') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Email *</label>
                                <input type="email" name="email"
                                       class="form-control"
                                       value="{{ old('email') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Số điện thoại *</label>
                                <input type="text" name="phone"
                                       class="form-control"
                                       value="{{ old('phone') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Công ty *</label>
                                <input type="text" name="company"
                                       class="form-control"
                                       value="{{ old('company') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Mã ZIP *</label>
                                <input type="text" name="zip"
                                       class="form-control"
                                       value="{{ old('zip') }}" required>
                            </div>

                            <div class="col-12 mb-3">
                                <label>Địa chỉ *</label>
                                <textarea name="address"
                                          class="form-control"
                                          required>{{ old('address') }}</textarea>
                            </div>

                            <div class="col-12 mb-3">
                                <label>Thành phố *</label>
                                <input type="text" name="city"
                                       class="form-control"
                                       value="{{ old('city') }}" required>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- SHIPPING --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-3">Phương thức giao hàng</h4>

                        <div class="form-check border p-3 mb-2 rounded">
                            <input class="form-check-input" type="radio"
                                   name="shipping" value="standard"
                                   checked>
                            <label class="form-check-label">
                                Giao hàng tiêu chuẩn
                            </label>
                        </div>

                        <div class="form-check border p-3 rounded">
                            <input class="form-check-input" type="radio"
                                   name="shipping" value="pickup">
                            <label class="form-check-label">
                                Nhận tại cửa hàng
                            </label>
                        </div>

                    </div>
                </div>

            </div>

            {{-- RIGHT --}}
            <div class="col-lg-4">

                <div class="card border shadow-sm sticky-top" style="top:20px;">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-3">Đơn hàng của bạn</h4>

                        @php
                            $cart = session('cart', []);
                            $products = $products ?? collect();
                            $total = 0;
                        @endphp

                        @forelse($products as $product)

                            @php
                                $qty = $cart[$product->id] ?? 0;
                                $subtotal = $product->price * $qty;
                                $total += $subtotal;
                            @endphp

                            <div class="d-flex mb-3">
                                <img src="{{ asset($product->thumbnail) }}"
                                     width="60"
                                     height="60"
                                     class="rounded border me-2"
                                     style="object-fit:cover;">

                                <div>
                                    <div class="fw-semibold">
                                        {{ $product->name }}
                                    </div>

                                    <small class="text-muted">
                                        SL: {{ $qty }}
                                    </small>

                                    <div class="text-danger fw-bold">
                                        {{ number_format($subtotal, 0, ',', '.') }}₫
                                    </div>
                                </div>
                            </div>

                        @empty
                            <p class="text-muted">Giỏ hàng trống</p>
                        @endforelse

                        <hr>

                        <div class="d-flex justify-content-between">
                            <strong>Tổng cộng</strong>
                            <strong class="text-primary">
                                {{ number_format($total, 0, ',', '.') }}₫
                            </strong>
                        </div>

                        <button class="btn btn-primary w-100 mt-3">
                            Đặt hàng
                        </button>

                        <a href="{{ route('client.cart') }}"
                           class="btn btn-outline-secondary w-100 mt-2">
                            Quay lại giỏ hàng
                        </a>

                    </div>
                </div>

            </div>

        </div>
    </form>
</div>
@endsection