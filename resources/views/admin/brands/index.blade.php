@extends('admin.master')

@section('title', 'Thương hiệu')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row mb-4 align-items-center">
            <div class="col-md-4">
                <h2 class="fw-semibold mb-0">
                    <i class="bi bi-tag-fill"></i> Quản lý Thương hiệu
                </h2>
            </div>
            <div class="col-md-5">
                <form action="{{ route('admin.brands.index') }}" method="GET" class="d-flex">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm thương hiệu..."
                            value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i> Tìm
                        </button>
                        @if (request('search'))
                            <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-danger" title="Xóa tìm kiếm">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-md-3 text-end">
                <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Thêm mới
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close"
                    data-bs-dismiss="alert"></button></div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button class="btn-close"
                    data-bs-dismiss="alert"></button></div>
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
                                            <img src="{{ Storage::url($brand->logo)  }}" alt="{{ $brand->name }}" width="40"
                                                height="40" style="object-fit:contain;">
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="fw-semibold">{{ $brand->name }}</td>
                                    <td class="text-muted">{{ $brand->slug }}</td>
                                    <td>{{ $brand->products_count }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.brands.edit', $brand->id) }}"
                                            class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Xóa thương hiệu này?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Chưa có thương hiệu nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $brands->links() }}
            </div>
        </div>
    </div>
@endsection