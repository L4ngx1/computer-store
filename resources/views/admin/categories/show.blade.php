@extends('admin.master')

@section('title', 'Chi tiết danh mục')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Chi tiết danh mục</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Quay lại</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Tên</dt><dd class="col-sm-9">{{ $category->name }}</dd>
            <dt class="col-sm-3">Slug</dt><dd class="col-sm-9">{{ $category->slug }}</dd>
            <dt class="col-sm-3">Số sản phẩm</dt><dd class="col-sm-9">{{ $category->products_count }}</dd>
            <dt class="col-sm-3">Trạng thái</dt><dd class="col-sm-9">{{ $category->is_active ? 'Hiển thị' : 'Ẩn' }}</dd>
        </dl>
        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary mt-3"><i class="bi bi-pencil me-1"></i>Chỉnh sửa</a>
    </div>
</div>
@endsection
