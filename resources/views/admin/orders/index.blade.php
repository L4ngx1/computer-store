@extends('admin.master')

@section('title', 'Đơn hàng')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.bootstrap5.min.css">
@endpush

@section('content')
    <style>
        .orders-hero {
            background: linear-gradient(135deg, #ffffff 0%, #f7f9fc 100%);
        }

        .orders-badge {
            letter-spacing: .04em;
            text-transform: uppercase;
            font-size: .72rem;
        }

        .item-table td,
        .item-table th {
            vertical-align: middle;
        }
    </style>

    <div class="d-flex flex-column gap-4">
        <div class="p-4 border rounded-4 shadow-sm orders-hero">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-1">
                <div>
                    <div class="orders-badge text-primary fw-semibold">Admin / Orders</div>
                    <h1 class="h3">Quản lý đơn hàng</h1>
                </div>
                <button class="btn btn-primary px-4" type="button" id="createOrderBtn">
                    <i class="bi bi-plus-lg me-1"></i>Thêm đơn hàng
                </button>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-3 align-items-end mb-4">
                    <div class="col-lg-5 col-md-6">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="search" id="searchInput" class="form-control" placeholder="Mã đơn, tên khách, email, số điện thoại">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Trạng thái</label>
                        <select id="statusFilter" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="pending">Chờ xử lý</option>
                            <option value="processing">Đang xử lý</option>
                            <option value="shipping">Đang giao</option>
                            <option value="completed">Hoàn thành</option>
                            <option value="cancelled">Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Số dòng</label>
                        <select id="perPageSelect" class="form-select">
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <button class="btn btn-outline-primary w-100" type="button" id="refreshBtn">
                            <i class="bi bi-arrow-clockwise me-1"></i>Tải lại
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Mã</th>
                                <th>Khách hàng</th>
                                <th>Liên hệ</th>
                                <th>Sản phẩm</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody">
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                    <div id="ordersSummary" class="text-muted small"></div>
                    <nav>
                        <ul class="pagination mb-0" id="ordersPagination"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @include('admin.orders.modals')

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
    @endpush

    @push('scripts')
        @include('admin.orders.scripts')
    @endpush
@endsection