@extends('admin.master')

@section('title', 'Đơn hàng')

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
    <h1 class="h3 mb-0">Quản lý đơn hàng</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end mb-4">
            <div class="col-lg-6">
                <label class="form-label">Tìm kiếm</label>
                <input type="search" name="q" class="form-control" value="{{ request('q') }}" placeholder="Mã đơn, tên khách, email, sđt">
            </div>
            <div class="col-lg-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $labels[$s][0] ?? $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Lọc</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Mã</th>
                        <th>Khách hàng</th>
                        <th>Liên hệ</th>
                        <th>SP</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="fw-semibold">#{{ $order->id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td class="small text-muted">{{ $order->customer_email }}<br>{{ $order->customer_phone }}</td>
                            <td>{{ $order->items_count }}</td>
                            <td class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                            <td><span class="badge bg-{{ $labels[$order->status][1] ?? 'secondary' }}">{{ $labels[$order->status][0] ?? $order->status }}</span></td>
                            <td class="small text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa đơn hàng này?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Không có đơn hàng nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $orders->links() }}
    </div>
</div>
@endsection
