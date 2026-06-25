@extends('layouts.site')

@section('title', 'Danh mục sản phẩm')

@section('content')
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh mục sản phẩm</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Sidebar Bộ lọc -->
        <div class="col-12 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <!-- Category Filter -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Danh mục</h5>
                        <div class="input-group input-group-sm mb-2">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control border-0 bg-light" placeholder="Tìm danh mục..." id="categorySearch">
                        </div>
                        <div class="list-group list-group-flush custom-scrollbar" style="max-height: 200px; overflow-y: auto;">
                            <a href="{{ route('client.catalog', array_merge(request()->except('category', 'page'), ['page' => 1])) }}" class="list-group-item list-group-item-action border-0 px-1 {{ !request('category') ? 'text-primary fw-bold' : 'text-secondary' }}">Tất cả sản phẩm</a>
                            @foreach($categories as $category)
                                <a href="{{ route('client.catalog', array_merge(request()->except('page'), ['category' => $category->slug, 'page' => 1])) }}" class="list-group-item list-group-item-action border-0 px-1 d-flex justify-content-between align-items-center category-item {{ request('category') == $category->slug ? 'text-primary fw-bold' : 'text-secondary' }}">
                                    <span>{{ $category->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div>
                        <h5 class="fw-bold mb-3">Thương hiệu</h5>
                        <div class="input-group input-group-sm mb-2">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control border-0 bg-light" placeholder="Tìm thương hiệu..." id="brandSearch">
                        </div>
                        <div class="list-group list-group-flush custom-scrollbar" style="max-height: 200px; overflow-y: auto;">
                            <a href="{{ route('client.catalog', array_merge(request()->except('brand', 'page'), ['page' => 1])) }}" class="list-group-item list-group-item-action border-0 px-1 {{ !request('brand') ? 'text-primary fw-bold' : 'text-secondary' }}">Tất cả thương hiệu</a>
                            @foreach($brands as $brand)
                                <a href="{{ route('client.catalog', array_merge(request()->except('page'), ['brand' => $brand->slug, 'page' => 1])) }}" class="list-group-item list-group-item-action border-0 px-1 d-flex justify-content-between align-items-center brand-item {{ request('brand') == $brand->slug ? 'text-primary fw-bold' : 'text-secondary' }}">
                                    <span>{{ $brand->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product List -->
        <div class="col-12 col-lg-9">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom">
                <h1 class="h3 fw-bold mb-3 mb-md-0">Sản phẩm</h1>
                <div class="d-flex align-items-center gap-2">
                    <label for="sortSelect" class="form-label mb-0 text-nowrap small text-secondary">Sắp xếp theo:</label>
                    <select class="form-select form-select-sm rounded-pill px-3" id="sortSelect" onchange="window.location.href=this.value">
                        <option value="{{ route('client.catalog', array_merge(request()->query(), ['sort' => 'newest', 'page' => 1])) }}" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="{{ route('client.catalog', array_merge(request()->query(), ['sort' => 'price_asc', 'page' => 1])) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                        <option value="{{ route('client.catalog', array_merge(request()->query(), ['sort' => 'price_desc', 'page' => 1])) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao xuống Thấp</option>
                    </select>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="alert alert-info text-center p-5 rounded-4 border-0 shadow-sm bg-white">
                    <i class="bi bi-box-seam display-4 text-secondary mb-3"></i>
                    <h5 class="fw-bold">Không tìm thấy sản phẩm nào!</h5>
                    <p class="text-secondary mb-0">Vui lòng thử bộ lọc khác hoặc quay lại sau.</p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 mb-5">
                    @foreach($products as $product)
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
                
                <div class="d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif
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
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #555;
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function addSearchFunctionality(inputId, itemClass) {
        const searchInput = document.getElementById(inputId);
        if (searchInput) {
            searchInput.addEventListener('keyup', function () {
                const filter = searchInput.value.toLowerCase();
                const items = document.querySelectorAll('.' + itemClass);
                items.forEach(function (item) {
                    const text = item.textContent || item.innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        item.style.display = "";
                    } else {
                        item.style.display = "none";
                    }
                });
            });
        }
    }

    addSearchFunctionality('categorySearch', 'category-item');
    addSearchFunctionality('brandSearch', 'brand-item');
});
</script>
@endpush
