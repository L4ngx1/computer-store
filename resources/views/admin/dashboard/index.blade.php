@extends('admin.master')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h3 mb-1">Admin Dashboard</h1>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Tổng đơn hàng</p>
                <h2 class="h4 mb-0">{{ number_format($counts['orders']) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Khách hàng</p>
                <h2 class="h4 mb-0">{{ number_format($counts['customers']) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Doanh thu hôm nay</p>
                <h2 class="h5 mb-0 text-success">{{ number_format((float) $counts['revenue_today'], 0, ',', '.') }} đ</h2>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-1">Doanh thu tháng này</p>
                <h2 class="h5 mb-0 text-success">{{ number_format((float) $counts['revenue_month'], 0, ',', '.') }} đ</h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4 justify-content-center">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <small class="text-muted d-block mb-1">Chờ duyệt</small>
                <strong class="fs-5">{{ number_format($counts['pending_orders']) }}</strong>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <small class="text-muted d-block mb-1">Đang xử lý</small>
                <strong class="fs-5">{{ number_format($counts['processing_orders']) }}</strong>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <small class="text-muted d-block mb-1">Đang giao</small>
                <strong class="fs-5">{{ number_format($counts['shipping_orders']) }}</strong>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <small class="text-muted d-block mb-1">Hoàn tất</small>
                <strong class="fs-5">{{ number_format($counts['completed_orders']) }}</strong>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <small class="text-muted d-block mb-1">Đã hủy</small>
                <strong class="fs-5">{{ number_format($counts['cancelled_orders']) }}</strong>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h2 class="h6 mb-0">5 đơn hàng mới nhất</h2>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3">Mã</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th class="text-end px-3">Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latestOrders as $order)
                            <tr>
                                <td class="px-3">#{{ $order->id }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ number_format((float) $order->total_amount, 0, ',', '.') }} đ</td>
                                <td>
                                    @php
                                    $statusMap = [
                                    'pending' => ['label' => 'Chờ duyệt', 'class' => 'warning'],
                                    'processing' => ['label' => 'Đang xử lý', 'class' => 'info'],
                                    'shipping' => ['label' => 'Đang giao', 'class' => 'primary'],
                                    'completed' => ['label' => 'Hoàn tất', 'class' => 'success'],
                                    'cancelled' => ['label' => 'Đã hủy', 'class' => 'danger'],
                                    ];
                                    $status = $statusMap[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'secondary'];
                                    @endphp
                                    <span class="badge text-bg-{{ $status['class'] }}">{{ $status['label'] }}</span>
                                </td>
                                <td class="text-end px-3">{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Chưa có đơn hàng nào.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h2 class="h6 mb-0">5 sản phẩm mới nhất</h2>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3">Tên sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Tồn kho</th>
                                <th class="text-end px-3">Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latestProducts as $product)
                            <tr>
                                <td class="px-3">{{ $product->name }}</td>
                                <td>{{ $product->category?->name ?? '-' }}</td>
                                <td>
                                    @if ($product->sale_price)
                                    <span class="text-danger fw-semibold">{{ number_format((float) $product->sale_price, 0, ',', '.') }} đ</span>
                                    <div class="small text-muted text-decoration-line-through">{{ number_format((float) $product->price, 0, ',', '.') }} đ</div>
                                    @else
                                    <span>{{ number_format((float) $product->price, 0, ',', '.') }} đ</span>
                                    @endif
                                </td>
                                <td>{{ number_format($product->stock) }}</td>
                                <td class="text-end px-3">{{ $product->created_at?->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Chưa có sản phẩm nào.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h2 class="h6 mb-0">Sản phẩm sắp hết hàng</h2>
                <a href="{{ route('admin.products.index') }}" class="small text-decoration-none">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3">Tên sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Thương hiệu</th>
                                <th class="text-end px-3">Tồn kho</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lowStockProducts as $product)
                            <tr>
                                <td class="px-3">{{ $product->name }}</td>
                                <td>{{ $product->category?->name ?? '-' }}</td>
                                <td>{{ $product->brand?->name ?? '-' }}</td>
                                <td class="text-end px-3">
                                    <span class="badge text-bg-{{ $product->stock === 0 ? 'danger' : 'warning' }}">
                                        {{ number_format($product->stock) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Không có sản phẩm nào sắp hết hàng.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
