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
<<<<<<< HEAD
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
=======
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">

                    {{-- Ảnh lớn chính --}}
                    <div class="mb-3">
                        <img id="mainImage"
                             src="{{ $product->thumbnail ? Storage::url($product->thumbnail) : '' }}"
                             alt="{{ $product->name }}"
                             class="img-fluid rounded"
                             style="width: 100%; height: 280px; object-fit: cover;">
                    </div>

                    {{-- Thumbnail strip: thumbnail + ảnh chi tiết --}}
                    <div class="d-flex gap-2 flex-wrap justify-content-center">
                        {{-- Ảnh chính (thumbnail) --}}
                        <div class="gallery-thumb active-thumb" onclick="switchImage(this, '{{ $product->thumbnail ? Storage::url($product->thumbnail) : '' }}')"
                             style="cursor:pointer; width:70px; height:70px; flex-shrink:0;">
                            <img src="{{ $product->thumbnail ? Storage::url($product->thumbnail) : '' }}"
                                 class="img-fluid rounded"
                                 style="width:100%; height:100%; object-fit:cover;">
                        </div>

                        {{-- Ảnh chi tiết --}}
                        @foreach($product->images as $image)
                        <div class="gallery-thumb" onclick="switchImage(this, '{{ Storage::url($image->image_path) }}')"
                             style="cursor:pointer; width:70px; height:70px; flex-shrink:0;">
                            <img src="{{ Storage::url($image->image_path) }}"
                                 class="img-fluid rounded"
                                 style="width:100%; height:100%; object-fit:cover;">
                        </div>
                        @endforeach
                    </div>

                    {{-- Info --}}
                    <div class="text-center mt-3">
                        <p class="text-muted small mb-1">ID sản phẩm: {{ $product->id }} &nbsp;|&nbsp; {{ $product->images->count() }} ảnh chi tiết</p>
                        <h5 class="card-title mb-1">{{ $product->name }}</h5>
                        <p class="text-muted mb-0"><code>{{ $product->sku }}</code></p>
                    </div>

                </div>
            </div>
>>>>>>> origin/main
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
<<<<<<< HEAD
    </div>
@endsection
=======
@endsection

@push('styles')
<style>
    .gallery-thumb {
        border: 2px solid transparent;
        border-radius: 6px;
        overflow: hidden;
        transition: border-color 0.2s, opacity 0.2s;
        opacity: 0.75;
    }
    .gallery-thumb:hover {
        border-color: #aaa;
        opacity: 1;
    }
    .gallery-thumb.active-thumb {
        border-color: #dc3545;
        opacity: 1;
    }
    #mainImage {
        transition: opacity 0.2s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    function switchImage(el, src) {
        // Đổi ảnh lớn
        const main = document.getElementById('mainImage');
        main.style.opacity = '0';
        setTimeout(() => {
            main.src = src;
            main.style.opacity = '1';
        }, 150);

        // Bỏ active tất cả thumb, thêm cho thumb được click
        document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active-thumb'));
        el.classList.add('active-thumb');
    }
</script>
@endpush
>>>>>>> origin/main
