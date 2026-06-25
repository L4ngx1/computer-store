@extends('admin.master')

@section('title', 'Đơn hàng #' . $order->id)

@section('content')
@php
    $labels = [
        'pending' => ['Chờ xử lý', 'warning'],
        'processing' => ['Đang xử lý', 'info'],
        'shipping' => ['Đang giao', 'primary'],
        'completed' => ['Hoàn thành', 'success'],
        'cancelled' => ['Đã hủy', 'danger'],
    ];
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Đơn hàng #{{ $order->id }}</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Quay lại</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Sản phẩm</div>
            <div class="card-body p-0">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr><th>Sản phẩm</th><th>Số lượng</th><th>Đơn giá</th><th class="text-end">Thành tiền</th></tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                <td class="text-end">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold"><td colspan="3" class="text-end">Tổng cộng</td><td class="text-end text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Thông tin khách hàng</div>
            <div class="card-body small">
                <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
                <p class="mb-1">{{ $order->customer_email }}</p>
                <p class="mb-1">{{ $order->customer_phone }}</p>
                <p class="mb-1">{{ $order->shipping_address }}</p>
                @if($order->note)<p class="text-muted mb-0">{{ $order->note }}</p>@endif
                <hr>
                <p class="mb-0">Thanh toán: <strong>{{ $order->payment_method }}</strong></p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Cập nhật trạng thái</div>
            <div class="card-body">
                <p>Hiện tại: <span class="badge bg-{{ $labels[$order->status][1] ?? 'secondary' }}">{{ $labels[$order->status][0] ?? $order->status }}</span></p>
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="status" class="form-select mb-3">
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ $labels[$s][0] ?? $s }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary w-100"><i class="bi bi-check-lg me-1"></i>Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
