@extends('admin.master')

@section('title', 'Sản phẩm')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <p class="text-uppercase text-muted small mb-1">Admin / Products</p>
        <h1 class="h3 mb-0">Quản lý Kho Sản phẩm</h1>
    </div>
    <div class="col-md-5">
        <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm danh mục..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="bi bi-search"></i> Tìm
                </button>
                @if (request('search'))
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-danger" title="Xóa tìm kiếm">
                    <i class="bi bi-x-lg"></i>
                </a>
                @endif
            </div>
        </form>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Thêm Sản phẩm
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

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
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info" title="Xem">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-warning" title="Sửa">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-outline-danger delete-btn" data-product-id="{{ $product->id }}" title="Xóa">
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
    <p class="mt-3 mb-0">Chưa có sản phẩm nào. <a href="{{ route('admin.products.create') }}">Thêm sản phẩm mới</a></p>
</div>
@endif

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn chắc chắn muốn xóa sản phẩm này? Hành động này không thể hoàn tác.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
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
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
        const url = '{{ route("admin.products.api.destroy", ["product" => "PLACEHOLDER"]) }}'.replace('PLACEHOLDER', deleteProductId);
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                window.location.reload();
            } else {
                alert('Lỗi khi xóa sản phẩm');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Lỗi khi xóa sản phẩm');
        }
    });
</script>
@endpush