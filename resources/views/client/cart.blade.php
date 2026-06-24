@extends('layouts.site')

@section('title', 'Giỏ Hàng')

@section('content')

<div class="container my-4">

    <h2 class="mb-4">Giỏ Hàng</h2>

    {{-- ========================= --}}
    {{-- CHECKOUT SUCCESS SCREEN --}}
    {{-- ========================= --}}
    @if(session('checkout_success'))

        <div class="d-flex justify-content-center align-items-center" style="min-height: 60vh; text-align:center;">
            <div>

                <h1 class="text-success fw-bold">
                    🎉 Đặt hàng thành công
                </h1>

                <p class="text-muted mt-2">
                    Cảm ơn bạn đã mua hàng tại cửa hàng của chúng tôi.
                </p>

                <a href="{{ route('client.catalog') }}" class="btn btn-primary mt-3">
                    Tiếp tục mua sắm
                </a>

            </div>
        </div>

    @else

        {{-- ========================= --}}
        {{-- MESSAGES --}}
        {{-- ========================= --}}
        @if(session('cart_success'))
            <div class="alert alert-info">
                {{ session('cart_success') }}
            </div>
        @endif

        @if(session('cart_error'))
            <div class="alert alert-danger">
                {{ session('cart_error') }}
            </div>
        @endif

        {{-- ========================= --}}
        {{-- EMPTY CART --}}
        {{-- ========================= --}}
        @if(empty($cart))

            <div class="alert alert-info">
                Giỏ hàng của bạn đang trống.
            </div>

            <a href="{{ route('client.catalog') }}" class="btn btn-primary">
                Tiếp tục mua sắm
            </a>

        @else

            @php $total = 0; @endphp

            {{-- ========================= --}}
            {{-- TABLE --}}
            {{-- ========================= --}}
            <table class="table table-bordered align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Sản phẩm</th>
                        <th width="150">Đơn giá</th>
                        <th width="180">Số lượng</th>
                        <th width="180">Thành tiền</th>
                        <th width="150">Cập nhật</th>
                        <th width="120">Xóa</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($products as $product)

                        @php
                            $qty = $cart[$product->id] ?? 0;
                            $subtotal = $product->price * $qty;
                            $total += $subtotal;
                        @endphp

                        <tr>
                            <td>
                                <strong>{{ $product->name }}</strong>
                            </td>

                            <td>
                                {{ number_format($product->price, 0, ',', '.') }} VNĐ
                            </td>

                            <td>
                                <form action="{{ route('client.cart.update') }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    <input type="number"
                                           name="quantities[{{ $product->id }}]"
                                           value="{{ $qty }}"
                                           min="1"
                                           class="form-control">
                            </td>

                            <td>
                                {{ number_format($subtotal, 0, ',', '.') }} VNĐ
                            </td>

                            <td>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Cập nhật
                                </button>
                                </form>
                            </td>

                            <td>
                                <form action="{{ route('client.cart.remove', $product->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @endforeach

                </tbody>
            </table>

            {{-- ========================= --}}
            {{-- TOTAL --}}
            {{-- ========================= --}}
            <div class="text-end mb-4">
                <h4>
                    Tổng tiền:
                    <span class="text-danger">
                        {{ number_format($total, 0, ',', '.') }} VNĐ
                    </span>
                </h4>
            </div>

            {{-- ========================= --}}
            {{-- ACTIONS --}}
            {{-- ========================= --}}
            <div class="d-flex justify-content-between">

                <a href="{{ route('client.catalog') }}" class="btn btn-secondary">
                    Tiếp tục mua sắm
                </a>

                <form action="{{ route('client.cart.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-warning">
                        Xóa toàn bộ giỏ hàng
                    </button>
                </form>

            </div>

        @endif

    @endif

</div>

@endsection