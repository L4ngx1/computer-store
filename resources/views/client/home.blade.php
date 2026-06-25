@extends('layouts.site')


@section('title', 'Trang chủ - Cửa hàng máy tính')

@section('content')
    <!-- Hero Carousel -->
    <div class="row mb-5">
        <div class="col-12">
            @if($featuredProducts->count() >= 1)
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    @foreach($featuredProducts->take(3) as $key => $product)
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}" aria-current="{{ $key == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $key + 1 }}"></button>
                    @endforeach
                </div>
                <div class="carousel-inner rounded-4 shadow-lg">
                    @foreach($featuredProducts->take(3) as $key => $product)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}" style="height: 450px;">
                            <img src="{{ $product->thumbnail ? Storage::url($product->thumbnail) : 'https://via.placeholder.com/1200x450' }}" class="d-block w-100 h-100" style="object-fit: cover;" alt="{{ $product->name }}">
                            <div class="carousel-caption d-none d-md-block text-start" style="background: rgba(0, 0, 0, 0.4); border-radius: 1rem; padding: 2rem; bottom: 10%; left: 5%; right: auto; max-width: 40%;">
                                <h2 class="display-5 fw-bold">{{ $product->name }}</h2>
                                <p class="lead">Sản phẩm nổi bật của chúng tôi</p>
                                <a href="{{ route('client.product', $product->slug) }}" class="btn btn-light btn-lg rounded-pill px-4 fw-bold text-primary">Xem chi tiết</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            @else
            <div class="p-5 text-center bg-primary text-white rounded-4 shadow position-relative overflow-hidden" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                <div class="position-relative z-1">
                    <h1 class="display-4 fw-bold mb-3">Welcome to Computer Store</h1>
                    <p class="lead mb-4 text-white-50">Các sản phẩm công nghệ tiên tiến nhất với mức giá ưu đãi.</p>
                    <a href="{{ route('client.catalog') }}" class="btn btn-light btn-lg rounded-pill px-5 fw-bold text-primary shadow-sm">Mua sắm ngay</a>
                </div>
            </div>
            @endif
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

    <!-- Thương hiệu đối tác -->
    @if($brands->isNotEmpty())
    <div class="mb-5">
        <h2 class="h4 fw-bold mb-4 border-bottom pb-2">Thương hiệu đối tác</h2>
        <div class="row row-cols-3 row-cols-md-4 row-cols-lg-6 g-4 align-items-center justify-content-center">
            @foreach($brands as $brand)
                <div class="col text-center">
                    <a href="{{ route('client.catalog', ['brand' => $brand->slug]) }}">
                        <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }}" class="img-fluid" style="max-height: 60px; filter: grayscale(100%); transition: filter 0.3s;" onmouseover="this.style.filter='none'" onmouseout="this.style.filter='grayscale(100%)'">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Why Choose Us Section -->
    <div class="mb-5 bg-light rounded-4 p-5">
        <h2 class="h4 fw-bold mb-4 text-center border-bottom pb-3">Tại sao chọn chúng tôi?</h2>
        <div class="row text-center g-4">
            <div class="col-md-3">
                <div class="p-3">
                    <i class="bi bi-truck display-4 text-primary mb-3"></i>
                    <h5 class="fw-bold">Giao hàng miễn phí</h5>
                    <p class="text-muted small">Cho đơn hàng trên 500.000đ</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <i class="bi bi-headset display-4 text-primary mb-3"></i>
                    <h5 class="fw-bold">Hỗ trợ 24/7</h5>
                    <p class="text-muted small">Hỗ trợ chuyên nghiệp</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <i class="bi bi-shield-check display-4 text-primary mb-3"></i>
                    <h5 class="fw-bold">Thanh toán an toàn</h5>
                    <p class="text-muted small">100% thanh toán an toàn</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <i class="bi bi-box-seam display-4 text-primary mb-3"></i>
                    <h5 class="fw-bold">Giá cả tốt nhất</h5>
                    <p class="text-muted small">Đảm bảo giá tốt</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm nổi bật -->
    <div class="mb-5 position-relative">
        <h2 class="h4 fw-bold mb-4 border-bottom pb-2">Sản phẩm nổi bật</h2>
        <div id="featuredProductsCarousel" class="carousel slide" data-bs-ride="false">
            <div class="carousel-inner">
                @forelse($featuredProducts->chunk(4) as $key => $chunk)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                            @foreach($chunk as $product)
                                <div class="col">
                                    <div class="card h-100 border-0 shadow-sm rounded-4 product-card overflow-hidden">
                                        <a href="{{ route('client.product', $product->slug) }}" class="text-decoration-none text-dark">
                                            <div class="product-image-container">
                                                @if($product->thumbnail)
                                                    <img src="{{ Storage::url($product->thumbnail) }}" class="product-image" alt="{{ $product->name }}">
                                                @else
                                                    <div class="product-image-placeholder">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                                </div>
                                                @if($product->sale_price)
                                                    <span class="position-absolute top-0 start-0 badge bg-danger m-3 px-2 py-1 rounded-pill shadow-sm">
                                                        Sale {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                                    </span>
                                                @endif
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
                @empty
                    <p>Không có sản phẩm nổi bật nào.</p>
                @endforelse
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#featuredProductsCarousel" data-bs-slide="prev" style="width: auto; left: -2rem;">
                <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#featuredProductsCarousel" data-bs-slide="next" style="width: auto; right: -2rem;">
                <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- Sản phẩm mới nhất -->
    <div class="mb-5 position-relative">
        <h2 class="h4 fw-bold mb-4 border-bottom pb-2">Sản phẩm mới nhất</h2>
        <div id="newProductsCarousel" class="carousel slide" data-bs-ride="false">
            <div class="carousel-inner">
                @forelse($newProducts->chunk(4) as $key => $chunk)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                            @foreach($chunk as $product)
                                <div class="col">
                                    <div class="card h-100 border-0 shadow-sm rounded-4 product-card overflow-hidden">
                                        <a href="{{ route('client.product', $product->slug) }}" class="text-decoration-none text-dark">
                                            <div class="product-image-container">
                                                @if($product->thumbnail)
                                                    <img src="{{ Storage::url($product->thumbnail) }}" class="product-image" alt="{{ $product->name }}">
                                                @else
                                                    <div class="product-image-placeholder">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                                </div>
                                                <span class="position-absolute top-0 start-0 badge bg-success m-3 px-2 py-1 rounded-pill shadow-sm">Mới</span>
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
                @empty
                    <p>Không có sản phẩm mới nào.</p>
                @endforelse
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#newProductsCarousel" data-bs-slide="prev" style="width: auto; left: -2rem;">
                <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#newProductsCarousel" data-bs-slide="next" style="width: auto; right: -2rem;">
                <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
>>>>>>> e63510a2bdd1d963eadaf3fb7bcd3dac7e7eed6d

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
    .product-image-container {
        position: relative;
        width: 100%;
        padding-top: 100%; /* 1:1 Aspect Ratio */
        overflow: hidden;
        background-color: #f8f9fa;
    }
    .product-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 1rem;
        transition: transform 0.3s ease;
    }
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    .product-image-placeholder {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
    }
</style>
@endpush
