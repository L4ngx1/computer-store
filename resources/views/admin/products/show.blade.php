@extends('admin.master')

@section('title', 'Chi tiết sản phẩm')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <p class="text-uppercase text-muted small mb-1">Admin / Products</p>
            <h1 class="h3 mb-0">Chi tiết Sản phẩm</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Sửa
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}" class="img-fluid rounded mb-3" style="max-height: 400px;">
                    <h5 class="card-title mb-1">{{ $product->name }}</h5>
                    <p class="text-muted mb-0"><code>{{ $product->sku }}</code></p>
                </div>
            </div>

            @if($product->images && $product->images->count() > 0)
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Ảnh thêm</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($product->images as $image)
                            <div class="col-md-6">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product image" class="img-fluid rounded">
                            </div>
                            @endforeach
</div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Thông tin cơ bản</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Tên Sản phẩm</label>
                            <p class="mb-0 fw-semibold">{{ $product->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">SKU</label>
                            <p class="mb-0"><code>{{ $product->sku }}</code></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Slug</label>
                            <p class="mb-0"><code>{{ $product->slug }}</code></p>
                        </div>
                        @if($product->summary)
                            <div class="col-12">
                                <label class="text-muted small">Mô tả ngắn</label>
                                <p class="mb-0">{{ $product->summary }}</p>
                            </div>
                        @endif
                        @if($product->description)
                            <div class="col-12">
                                <label class="text-muted small">Mô tả chi tiết</label>
                                <p class="mb-0">{{ $product->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Giá & Kho hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="text-muted small">Giá gốc</label>
                            <p class="mb-0 fs-5 fw-semibold">{{ number_format($product->price) }} ₫</p>
                        </div>
                        @if($product->sale_price)
                            <div class="col-md-4">
                                <label class="text-muted small">Giá khuyến mãi</label>
                                <p class="mb-0 fs-5 fw-semibold text-success">{{ number_format($product->sale_price) }} ₫</p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Tiết kiệm</label>
                                <p class="mb-0 fs-5 fw-semibold text-danger">-{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%</p>
                            </div>
                        @endif
                        <div class="col-md-4">
                            <label class="text-muted small">Số lượng tồn kho</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">{{ $product->stock }} cái</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Phân loại</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Danh mục</label>
                            <p class="mb-0"><span class="badge bg-primary">{{ $product->category?->name ?? '-' }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Thương hiệu</label>
                            <p class="mb-0"><span class="badge bg-info">{{ $product->brand?->name ?? '-' }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Trạng thái</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Bán hàng</label>
                            <p class="mb-0">
                                @if($product->is_active)
                                    <span class="badge bg-success">Bán (Hiển thị & cho phép mua)</span>
                                @else
                                    <span class="badge bg-secondary">Dừng bán</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Nổi bật</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $product->is_featured ? 'warning' : 'light text-dark' }}">
                                    {{ $product->is_featured ? 'Nổi bật' : 'Thường' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($product->attributes && $product->attributes->count() > 0)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Thuộc tính</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tbody>
                                @foreach($product->attributes as $attr)
                                    <tr>
                                        <td class="fw-semibold">{{ $attr->name }}</td>
                                        <td>{{ $attr->value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Thông tin hệ thống</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
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
@endsection
