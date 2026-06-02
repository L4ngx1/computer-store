@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0"> Quản lý Kho Sản phẩm</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Thêm Sản phẩm
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($products->count() > 0)
        <div class="table-responsive bg-white rounded shadow-sm">
            <table class="table table-hover mb-0">
                <thead class="table-light border-bottom">
                    <tr>
                        <th>ID</th>
                        <th>Tên Sản phẩm</th>
                        <th>SKU</th>
                        <th>Danh mục</th>
                        <th>Thương hiệu</th>
                        <th>Giá</th>
                        <th>Kho</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td><strong>#{{ $product->id }}</strong></td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $product->slug }}</small>
                            </td>
                            <td><code>{{ $product->sku }}</code></td>
                            <td>{{ $product->category?->name ?? '-' }}</td>
                            <td>{{ $product->brand?->name ?? '-' }}</td>
                            <td>{{ number_format($product->price) }} ₫</td>
                            <td>
                                <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success"> Bán</span>
                                @else
                                    <span class="badge bg-secondary"> Dừng</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.products.show', $product) }}" 
                                       class="btn btn-outline-info" title="Xem">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="btn btn-outline-warning" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger delete-btn" 
                                            data-product-id="{{ $product->id }}" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-inbox fs-1"></i>
            <p class="mt-3 mb-0">Chưa có sản phẩm nào. <a href="{{ route('admin.products.create') }}">Thêm sản phẩm mới</a></p>
        </div>
    @endif
</div>

<!-- Delete Modal -->
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

<script>
    let deleteProductId = null;
    
    // Delete button click handlers
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            deleteProductId = btn.getAttribute('data-product-id');
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
    });
    
    document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
        try {
            const response = await fetch(`/api/admin/products/${deleteProductId}`, {
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
@endsection
