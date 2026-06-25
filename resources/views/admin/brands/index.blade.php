@extends('admin.master')

@section('title', 'Thương hiệu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Quản lý thương hiệu</h1>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Thêm thương hiệu</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Logo</th>
                        <th>Tên</th>
                        <th>Slug</th>
                        <th>Sản phẩm</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                        <tr>
                            <td>
                                @if($brand->logo)
                                    <img src="{{ $brand->logo }}" alt="{{ $brand->name }}" width="40" height="40" style="object-fit:contain;">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $brand->name }}</td>
                            <td class="text-muted">{{ $brand->slug }}</td>
                            <td>{{ $brand->products_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa thương hiệu này?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Chưa có thương hiệu nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $brands->links() }}
    </div>
</div>
@endsection
