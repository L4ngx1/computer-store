@extends('admin.master')

@section('title', isset($brand) ? 'Sửa thương hiệu' : 'Thêm thương hiệu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">{{ isset($brand) ? 'Sửa thương hiệu' : 'Thêm thương hiệu' }}</h1>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Quay lại</a>
</div>

@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ isset($brand) ? route('admin.brands.update', $brand->id) : route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @isset($brand) @method('PUT') @endisset

            <div class="mb-3">
                <label class="form-label">Tên thương hiệu *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $brand->name ?? '') }}" required>
            </div>
            <div class="mb-4">
                <label class="form-label" for="brandLogo">Logo thương hiệu</label>
                <input type="file" id="brandLogo" name="logo" class="form-control" accept="image/png,image/jpeg,image/webp">
                <div class="form-text">Chọn ảnh PNG, JPG hoặc WebP, tối đa 2 MB.</div>
                @if(isset($brand) && $brand->logo)
                    @php($brandLogoUrl = filter_var($brand->logo, FILTER_VALIDATE_URL) ? $brand->logo : Storage::disk('public')->url($brand->logo))
                    <img src="{{ $brandLogoUrl }}" alt="Logo {{ $brand->name }}" class="mt-2 rounded" style="max-width: 120px; max-height: 90px; object-fit: contain;">
                @endif
            </div>

            <button class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Lưu</button>
        </form>
    </div>
</div>
@endsection
