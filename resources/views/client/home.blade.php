@extends('layouts.site')

@section('title', 'Trang chủ - Cửa hàng máy tính')

@section('content')
    <!-- Banner Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="p-5 text-center bg-primary text-white rounded-4 shadow position-relative overflow-hidden" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                <div class="position-relative z-1">
                    <h1 class="display-4 fw-bold mb-3">Welcome to Computer Store</h1>
                    <p class="lead mb-4 text-white-50">Các sản phẩm công nghệ tiên tiến nhất với mức giá ưu đãi.</p>
                    <a href="{{ route('client.catalog') }}" class="btn btn-light btn-lg rounded-pill px-5 fw-bold text-primary shadow-sm">Mua sắm ngay</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh mục nổi bật -->
    <div class="mb-5">
        <h2 class="h4 fw-bold mb-4 border-bottom pb-2">Danh mục nổi bật</h2>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3 text-center">
            @foreach($categories as $category)
                <div class="col">
                    <a href="{{ route('client.catalog', ['category' => $category->slug]) }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm rounded-4 hover-shadow transition-all bg-white">
                            <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
                                @if($category->image)
                                    <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="img-fluid mb-2" style="max-height: 50px;">
                                @else
                                    <i class="bi bi-laptop display-4 text-primary mb-2"></i>
                                @endif
                                <h3 class="h6 text-dark fw-bold mb-0 mt-2">{{ $category->name }}</h3>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Sản phẩm nổi bật -->
    <div class="mb-5">
        <h2 class="h4 fw-bold mb-4 border-bottom pb-2">Sản phẩm nổi bật</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($featuredProducts as $product)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 product-card overflow-hidden">
                        <a href="{{ route('client.product', $product->slug) }}" class="text-decoration-none text-dark">
                            <div class="position-relative bg-white text-center">
                                @if($product->thumbnail)
                                    <img src="{{ Storage::url($product->thumbnail) }}" class="card-img-top p-3" alt="{{ $product->name }}" style="height: 200px; object-fit: contain;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                @if($product->sale_price)
                                    <span class="position-absolute top-0 start-0 badge bg-danger m-3 px-2 py-1 rounded-pill shadow-sm">
                                        Sale {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                    </span>
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column bg-white">
                                <p class="text-muted small mb-1">{{ $product->category->name ?? 'Không phân loại' }}</p>
                                <h3 class="h6 fw-bold mb-2 text-truncate-2">{{ $product->name }}</h3>
                                <div class="mt-auto">
                                    @if($product->sale_price)
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="fw-bold text-danger fs-5">{{ number_format($product->sale_price, 0, ',', '.') }}đ</span>
                                            <span class="text-muted text-decoration-line-through small">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                        </div>
                                    @else
                                        <div class="fw-bold text-primary fs-5 mb-2">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                                    @endif
                                    <button class="btn btn-outline-primary w-100 rounded-pill fw-bold" onclick="event.preventDefault(); alert('Chức năng thêm giỏ hàng sẽ được cập nhật!');">
                                        <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Sản phẩm mới nhất -->
    <div class="mb-5">
        <h2 class="h4 fw-bold mb-4 border-bottom pb-2">Sản phẩm mới nhất</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($newProducts as $product)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 product-card overflow-hidden">
                        <a href="{{ route('client.product', $product->slug) }}" class="text-decoration-none text-dark">
                            <div class="position-relative bg-white text-center">
                                @if($product->thumbnail)
                                    <img src="{{ Storage::url($product->thumbnail) }}" class="card-img-top p-3" alt="{{ $product->name }}" style="height: 200px; object-fit: contain;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                <span class="position-absolute top-0 start-0 badge bg-success m-3 px-2 py-1 rounded-pill shadow-sm">Mới</span>
                            </div>
                            <div class="card-body d-flex flex-column bg-white">
                                <p class="text-muted small mb-1">{{ $product->category->name ?? 'Không phân loại' }}</p>
                                <h3 class="h6 fw-bold mb-2 text-truncate-2">{{ $product->name }}</h3>
                                <div class="mt-auto">
                                    @if($product->sale_price)
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="fw-bold text-danger fs-5">{{ number_format($product->sale_price, 0, ',', '.') }}đ</span>
                                            <span class="text-muted text-decoration-line-through small">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                        </div>
                                    @else
                                        <div class="fw-bold text-primary fs-5 mb-2">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                                    @endif
                                    <button class="btn btn-outline-primary w-100 rounded-pill fw-bold" onclick="event.preventDefault(); alert('Chức năng thêm giỏ hàng sẽ được cập nhật!');">
                                        <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection

@push('styles')
<style>
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 2.8em;
    }
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    }
    .hover-shadow {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-shadow:hover {
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
        transform: translateY(-3px);
    }
</style>
@endpush
