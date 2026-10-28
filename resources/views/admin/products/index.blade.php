@extends('admin.master')

@section('title', 'Sản phẩm')

@section('content')
<div class="card admin-filter-card"><div class="card-body">
    <form action="{{ route('admin.products.index') }}" method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-lg-6"><label class="form-label small fw-semibold">Tìm kiếm</label><input type="search" name="search" class="form-control" placeholder="Tên sản phẩm hoặc SKU..." value="{{ request('search') }}"></div>
        <div class="col-6 col-lg-2"><label class="form-label small fw-semibold">Trạng thái</label><select name="status" class="form-select"><option value="">Tất cả</option><option value="active" @selected(request('status') === 'active')>Đang bán</option><option value="inactive" @selected(request('status') === 'inactive')>Đã ẩn</option></select></div>
        <div class="col-6 col-lg-2"><label class="form-label small fw-semibold">Tồn kho</label><select name="stock" class="form-select"><option value="">Tất cả</option><option value="low" @selected(request('stock') === 'low')>Sắp hết (≤ 5)</option></select></div>
        <div class="col-12 col-lg-2 d-flex gap-2"><button class="btn btn-primary flex-grow-1"><i class="bi bi-funnel me-1"></i>Lọc</button><a href="{{ route('admin.products.index') }}" class="btn btn-light border" title="Xóa bộ lọc"><i class="bi bi-arrow-counterclockwise"></i></a></div>
    </form>
</div></div>

<div class="d-flex justify-content-end mb-3">
    <a data-admin-modal data-modal-title="Thêm sản phẩm" href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Thêm sản phẩm
    </a>
</div>

@if($products->count() > 0)
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">ID</th>
                    <th>Tên Sản phẩm</th>
                    <th>SKU</th>
                    <th>Danh mục</th>
                    <th>Thương hiệu</th>
                    <th>Giá</th>
                    <th>Kho</th>
                    <th>Trạng thái</th>
                    <th class="text-end pe-3">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td class="ps-3 fw-semibold">#{{ $product->id }}</td>
                    <td>
                        <div class="fw-semibold">{{ $product->name }}</div>
                        <div class="small text-muted">{{ $product->slug }}</div>
                    </td>
                    <td><code>{{ $product->sku }}</code></td>
                    <td>{{ $product->category?->name ?? '-' }}</td>
                    <td>{{ $product->brand?->name ?? '-' }}</td>
                    <td>{{ number_format($product->price) }} ₫</td>
                    <td>
                        <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">{{ $product->stock }}</span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                            {{ $product->is_active ? 'Bán' : 'Dừng' }}
                        </span>
                    </td>
                    <td class="text-end pe-3">
                        <div class="btn-group btn-group-sm" role="group">
                            <a data-admin-modal data-modal-title="Chi tiết sản phẩm" href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info" title="Xem">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a data-admin-modal data-modal-title="Chỉnh sửa sản phẩm" href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-warning" title="Sửa">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-outline-danger delete-btn" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-delete-url="{{ route('admin.products.api.destroy', $product) }}" title="Xóa">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@else
<div class="alert alert-info text-center py-5">
    <i class="bi bi-inbox fs-1"></i>
    <p class="mt-3 mb-0">Chưa có sản phẩm nào. <a data-admin-modal data-modal-title="Thêm sản phẩm" href="{{ route('admin.products.create') }}">Thêm sản phẩm mới</a></p>
</div>
@endif

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="small text-uppercase text-muted fw-semibold mb-1">Quản lý sản phẩm</div>
                    <h5 class="modal-title">Xoá sản phẩm</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div>
                    <div class="small text-uppercase text-muted fw-semibold">Sản phẩm được chọn</div>
                    <span class="delete-product-name" id="deleteProductName">Sản phẩm</span>
                </div>
                <div class="delete-product-warning">Thao tác xoá sẽ gỡ sản phẩm khỏi cửa hàng và không thể hoàn tác.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Xoá sản phẩm</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let deleteProductId = null;

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            deleteProductId = btn.getAttribute('data-product-id');
            document.getElementById('deleteProductName').textContent = btn.getAttribute('data-product-name') || 'Sản phẩm';
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
        const deleteBtn = document.querySelector(`.delete-btn[data-product-id="${deleteProductId}"]`);
        const url = deleteBtn ? deleteBtn.getAttribute('data-delete-url') : '';
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json().catch(() => ({}));
                sessionStorage.setItem('admin-notification', JSON.stringify({
                    message: data.message || 'Sản phẩm đã được xóa.',
                    type: 'success'
                }));
                window.location.reload();
            } else {
                const data = await response.json().catch(() => ({}));
                window.adminNotify(data.message || 'Lỗi khi xóa sản phẩm.', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            window.adminNotify('Lỗi khi xóa sản phẩm.', 'error');
        }
    });
</script>
@endpush
