@extends('layouts.site')

@section('title', 'Giỏ Hàng')

@section('content')

{{-- CSS Tùy chỉnh để nút bấm trông đẹp và hiện đại hơn --}}
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
        padding: 10px 20px;
        font-weight: 600;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
</style>

<div class="container my-5">

    <h2 class="mb-4 fw-bold text-primary">🛒 Giỏ Hàng của bạn</h2>

    @if(session('checkout_success'))
        <div class="d-flex justify-content-center align-items-center" style="min-height: 50vh; text-align:center;">
            <div>
                <h1 class="text-success fw-bold">🎉 Đặt hàng thành công</h1>
                <p class="text-muted mt-2">Cảm ơn bạn đã mua hàng tại cửa hàng của chúng tôi.</p>
                <a href="{{ route('client.catalog') }}" class="btn btn-primary btn-lg mt-3">Tiếp tục mua sắm</a>
            </div>
        </div>
    @else

        @if(session('cart_success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('cart_success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($cartItems->isEmpty())
            <div class="card p-5 text-center shadow-sm">
                <h4 class="text-muted">Giỏ hàng của bạn đang trống!</h4>
                <div class="mt-3">
                    <a href="{{ route('client.catalog') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-shopping-bag"></i> Khám phá sản phẩm
                    </a>
                </div>
            </div>
        @else

            <table class="table table-hover align-middle shadow-sm bg-white rounded">
                <thead class="table-dark">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cartItems as $item)
                        @php
                            $product = $item->product;
                            $qty = $item->quantity;
                            $price = $product->sale_price ?? $product->price;
                            $subtotal = $price * $qty;
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>{{ number_format($price, 0, ',', '.') }} VNĐ</td>
                            <td>
                                <form action="{{ route('client.cart.update') }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    <input type="number" name="quantities[{{ $product->id }}]" value="{{ $qty }}" min="1" class="form-control form-control-sm text-center" style="width: 70px;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Cập nhật</button>
                                </form>
                            </td>
                            <td class="fw-bold text-danger">{{ number_format($subtotal, 0, ',', '.') }} VNĐ</td>
                            <td class="text-center">
                                <form action="{{ route('client.cart.remove', $product->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="row mt-4">
                <div class="col-md-6">
                    <a href="{{ route('client.catalog') }}" class="btn btn-outline-secondary btn-modern">
                        ← Tiếp tục mua sắm
                    </a>
                </div>
                <div class="col-md-6 text-end">
                    <h3 class="mb-3">Tổng tiền: <span class="text-danger fw-bold">{{ number_format($total, 0, ',', '.') }} VNĐ</span></h3>
                    
                    <div class="d-flex justify-content-end gap-3">
                        <form action="{{ route('client.cart.clear') }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-warning btn-modern">Xóa toàn bộ</button>
                        </form>
                        
                        <a href="{{ route('client.checkout') }}" class="btn btn-gradient btn-modern btn-lg px-5">
                            Thanh toán ngay →
                        </a>
                    </div>
                </div>
            </div>

        @endif
    @endif
</div>
@endsection