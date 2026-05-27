@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">👁️ Chi tiết Sản phẩm</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Sửa
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Ảnh sản phẩm -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}" class="img-fluid rounded mb-3" style="max-height: 400px;">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="text-muted mb-0"><code>{{ $product->sku }}</code></p>
                </div>
            </div>

            @if($product->images && $product->images->count() > 0)
                <div class="card shadow-sm mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">Ảnh thêm</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($product->images as $image)
                                <div class="col-md-6">
                                    <img src="{{ $image->image_path }}" alt="Product image" class="img-fluid rounded">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-8">
            <!-- Thông tin cơ bản -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">ℹ️ Thông tin cơ bản</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Tên Sản phẩm</label>
                            <p class="mb-0"><strong>{{ $product->name }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">SKU</label>
                            <p class="mb-0"><code>{{ $product->sku }}</code></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Slug (URL thân thiện)</label>
                            <p class="mb-0"><code>{{ $product->slug }}</code></p>
                        </div>
                    </div>
                    @if($product->summary)
                        <div class="mb-3">
                            <label class="text-muted small">Mô tả ngắn</label>
                            <p class="mb-0">{{ $product->summary }}</p>
                        </div>
                    @endif
                    @if($product->description)
                        <div class="mb-3">
                            <label class="text-muted small">Mô tả chi tiết</label>
                            <p class="mb-0">{{ $product->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Giá & Kho -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">💰 Giá & Kho hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="text-muted small">Giá gốc</label>
                            <p class="mb-0 fs-5"><strong>{{ number_format($product->price) }} ₫</strong></p>
                        </div>
                        @if($product->sale_price)
                            <div class="col-md-4">
                                <label class="text-muted small">Giá khuyến mãi</label>
                                <p class="mb-0 fs-5"><strong class="text-success">{{ number_format($product->sale_price) }} ₫</strong></p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Tiết kiệm</label>
                                <p class="mb-0 fs-5"><strong class="text-danger">-{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%</strong></p>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="text-muted small">Số lượng tồn kho</label>
                            <p class="mb-0 fs-5">
                                <strong class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                                    {{ $product->stock }} cái
                                </strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phân loại -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">📁 Phân loại</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Danh mục</label>
                            <p class="mb-0">
                                <span class="badge bg-primary">{{ $product->category?->name ?? '-' }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Thương hiệu</label>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ $product->brand?->name ?? '-' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trạng thái -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">🔧 Trạng thái</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Bán hàng</label>
                            <p class="mb-0">
                                @if($product->is_active)
                                    <span class="badge bg-success">✓ Bán (Hiển thị & cho phép mua)</span>
                                @else
                                    <span class="badge bg-secondary">✗ Dừng bán</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Nổi bật</label>
                            <p class="mb-0">
                                @if($product->is_featured)
                                    <span class="badge bg-warning">⭐ Nổi bật (Hiển thị trên trang chủ)</span>
                                @else
                                    <span class="badge bg-light text-dark">Thường</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thuộc tính sản phẩm -->
            @if($product->attributes && $product->attributes->count() > 0)
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">🏷️ Thuộc tính</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tbody>
                                @foreach($product->attributes as $attr)
                                    <tr>
                                        <td><strong>{{ $attr->name }}</strong></td>
                                        <td>{{ $attr->value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Thông tin hệ thống -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">⏰ Thông tin hệ thống</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-muted small">Tạo lúc</label>
                            <p class="mb-0">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Cập nhật lúc</label>
                            <p class="mb-0">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
