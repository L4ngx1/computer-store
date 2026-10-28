@extends('admin.master')

@section('title', 'Danh mục')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4 align-items-center">
        <div class="col-12">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="d-flex">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm danh mục..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search"></i> Tìm
                    </button>
                    @if (request('search'))
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-danger" title="Xóa tìm kiếm">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <a data-admin-modal data-modal-title="Thêm danh mục" href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm mới
        </a>
    </div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Tên</th>
                        <th>Slug</th>
                        <th>Sản phẩm</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td class="text-muted">{{ $category->slug }}</td>
                            <td>{{ $category->products_count }}</td>
                            <td>
                                @if($category->is_active)
                                    <span class="badge bg-success">Hiển thị</span>
                                @else
                                    <span class="badge bg-secondary">Ẩn</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a data-admin-modal data-modal-title="Chi tiết danh mục" href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                <a data-admin-modal data-modal-title="Chỉnh sửa danh mục" href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa danh mục này?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Chưa có danh mục nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $categories->links() }}
    </div>
</div>
</div>
@endsection
