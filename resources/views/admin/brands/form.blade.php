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
        <form action="{{ isset($brand) ? route('admin.brands.update', $brand->id) : route('admin.brands.store') }}" method="POST">
            @csrf
            @isset($brand) @method('PUT') @endisset

            <div class="mb-3">
                <label class="form-label">Tên thương hiệu *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $brand->name ?? '') }}" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Logo (URL)</label>
                <input type="text" name="logo" class="form-control" value="{{ old('logo', $brand->logo ?? '') }}">
            </div>

            <button class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Lưu</button>
        </form>
    </div>
</div>
@endsection
