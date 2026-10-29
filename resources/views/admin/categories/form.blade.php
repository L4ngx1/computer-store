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
        <form action="{{ isset($category) ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @isset($category) @method('PUT') @endisset

            <div class="mb-3">
                <label class="form-label">Tên danh mục *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="categoryImage">Ảnh danh mục</label>
                <input type="file" id="categoryImage" name="image" class="form-control" accept="image/png,image/jpeg,image/webp">
                <div class="form-text">Chọn ảnh PNG, JPG hoặc WebP, tối đa 2 MB.</div>
                @if(isset($category) && $category->image)
                    @php($categoryImageUrl = filter_var($category->image, FILTER_VALIDATE_URL) ? $category->image : Storage::disk('public')->url($category->image))
                    <img src="{{ $categoryImageUrl }}" alt="Ảnh {{ $category->name }}" class="mt-2 rounded" style="max-width: 120px; max-height: 90px; object-fit: cover;">
                @endif
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
