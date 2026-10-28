@extends('admin.master')

@section('title', 'Dashboard')

@section('content')
@php
    $statusMap = [
        'pending' => ['Chờ xử lý', 'warning'],
        'processing' => ['Đang xử lý', 'info'],
        'shipping' => ['Đang giao', 'primary'],
        'completed' => ['Hoàn thành', 'success'],
        'cancelled' => ['Đã hủy', 'danger'],
    ];
@endphp

<div class="page-heading">
    <div></div>
    <div class="d-flex gap-2">
        <a data-admin-modal data-modal-title="Thêm sản phẩm" href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Thêm sản phẩm</a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-light border"><i class="bi bi-arrow-up-right me-1"></i>Xem đơn hàng</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card h-100"><div class="card-body">
            <div class="stat-icon"><i class="bi bi-bag-check"></i></div>
            <h2>{{ number_format($counts['orders']) }}</h2><p>Tổng đơn hàng</p>
        </div></div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card h-100"><div class="card-body">
            <div class="stat-icon" style="color:#059669;background:#ecfdf5"><i class="bi bi-currency-dollar"></i></div>
            <h2>{{ number_format((float) $counts['revenue_month'], 0, ',', '.') }}đ</h2><p>Doanh thu tháng này</p>
        </div></div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card h-100"><div class="card-body">
            <div class="stat-icon" style="color:#7c3aed;background:#f5f3ff"><i class="bi bi-people"></i></div>
            <h2>{{ number_format($counts['customers']) }}</h2><p>Khách hàng</p>
        </div></div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card h-100"><div class="card-body">
            <div class="stat-icon" style="color:#d97706;background:#fffbeb"><i class="bi bi-exclamation-triangle"></i></div>
            <h2>{{ number_format($counts['low_stock_products']) }}</h2><p>Sản phẩm sắp hết hàng</p>
        </div></div>
    </div>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['pending_orders', 'Chờ xử lý', 'warning', 'bi-hourglass-split'],
        ['processing_orders', 'Đang xử lý', 'info', 'bi-box-seam'],
        ['shipping_orders', 'Đang giao', 'primary', 'bi-truck'],
        ['completed_orders', 'Hoàn thành', 'success', 'bi-check2-circle'],
        ['cancelled_orders', 'Đã hủy', 'danger', 'bi-x-circle'],
    ] as [$key, $label, $color, $icon])
        <div class="col-6 col-md">
            <a href="{{ route('admin.orders.index', ['status' => str_replace('_orders', '', $key)]) }}" class="card text-decoration-none h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <i class="bi {{ $icon }} text-{{ $color }} fs-5"></i>
                    <div><div class="fw-bold">{{ number_format($counts[$key]) }}</div><small class="text-muted">{{ $label }}</small></div>
                </div>
            </a>
        </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-12 col-xl-7">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div><h2 class="h6 mb-1">Đơn hàng gần đây</h2><small class="text-muted">Theo dõi các đơn mới nhất</small></div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light border">Xem tất cả</a>
            </div>
            <div class="table-responsive"><table class="table align-middle mb-0">
                <thead><tr><th class="ps-3">Đơn hàng</th><th>Khách hàng</th><th>Tổng tiền</th><th>Trạng thái</th><th class="text-end pe-3">Thời gian</th></tr></thead>
                <tbody>
                    @forelse($latestOrders as $order)
                        @php($status = $statusMap[$order->status] ?? [ucfirst($order->status), 'secondary'])
                        <tr>
                            <td class="ps-3"><a data-admin-modal data-modal-title="Chi tiết đơn hàng #{{ $order->id }}" class="fw-semibold text-decoration-none" href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a></td>
                            <td>{{ $order->customer_name }}</td>
                            <td class="fw-semibold">{{ number_format((float) $order->total_amount, 0, ',', '.') }}đ</td>
                            <td><span class="badge text-bg-{{ $status[1] }}">{{ $status[0] }}</span></td>
                            <td class="text-end pe-3 small text-muted">{{ $order->created_at?->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-5">Chưa có đơn hàng nào.</td></tr>
                    @endforelse
                </tbody>
            </table></div>
        </div>
    </div>
    <div class="col-12 col-xl-5">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div><h2 class="h6 mb-1">Cảnh báo tồn kho</h2><small class="text-muted">Cần được bổ sung sớm</small></div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-light border">Kho hàng</a>
            </div>
            <div class="card-body p-0">
                @forelse($lowStockProducts as $product)
                    <div class="d-flex align-items-center justify-content-between gap-3 px-3 py-3 border-bottom">
                        <div class="text-truncate"><div class="fw-semibold text-truncate">{{ $product->name }}</div><small class="text-muted">{{ $product->sku }}</small></div>
                        <span class="badge text-bg-{{ $product->stock === 0 ? 'danger' : 'warning' }} flex-shrink-0">{{ $product->stock }} còn lại</span>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">Tồn kho đang ổn định.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <div><h2 class="h6 mb-1">Sản phẩm mới nhất</h2><small class="text-muted">Các sản phẩm vừa được thêm vào catalog</small></div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-light border">Quản lý sản phẩm</a>
    </div>
    <div class="table-responsive"><table class="table align-middle mb-0">
        <thead><tr><th class="ps-3">Sản phẩm</th><th>Danh mục</th><th>Giá bán</th><th>Tồn kho</th><th>Trạng thái</th><th class="text-end pe-3">Ngày tạo</th></tr></thead>
        <tbody>
            @forelse($latestProducts as $product)
                <tr>
                    <td class="ps-3"><div class="fw-semibold">{{ $product->name }}</div><small class="text-muted">{{ $product->sku }}</small></td>
                    <td>{{ $product->category?->name ?? '-' }}</td>
                    <td>@if($product->sale_price)<span class="text-danger fw-semibold">{{ number_format((float) $product->sale_price, 0, ',', '.') }}đ</span>@else{{ number_format((float) $product->price, 0, ',', '.') }}đ@endif</td>
                    <td><span class="badge text-bg-{{ $product->stock <= 5 ? 'warning' : 'success' }}">{{ number_format($product->stock) }}</span></td>
                    <td><span class="badge text-bg-{{ $product->is_active ? 'success' : 'secondary' }}">{{ $product->is_active ? 'Đang bán' : 'Đã ẩn' }}</span></td>
                    <td class="text-end pe-3 small text-muted">{{ $product->created_at?->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-5">Chưa có sản phẩm nào.</td></tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endsection
