@extends('admin.master')

@section('title', isset($category) ? 'Sửa danh mục' : 'Thêm danh mục')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">{{ isset($category) ? 'Sửa danh mục' : 'Thêm danh mục' }}</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Quay lại</a>
</div>

@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ isset($category) ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}" method="POST">
            @csrf
            @isset($category) @method('PUT') @endisset

            <div class="mb-3">
                <label class="form-label">Tên danh mục *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Ảnh (URL)</label>
                <input type="text" name="image" class="form-control" value="{{ old('image', $category->image ?? '') }}">
            </div>
            <div class="form-check mb-4">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Hiển thị danh mục</label>
            </div>

            <button class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Lưu</button>
        </form>
    </div>
</div>
@endsection
