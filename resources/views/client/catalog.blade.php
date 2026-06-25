@extends('layouts.site')

@section('title', 'Danh mục sản phẩm')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh mục sản phẩm</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-12 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Danh mục</h5>
                        <input type="text" class="form-control border-0 bg-light mb-2" placeholder="Tìm danh mục..." id="categorySearch">
                        <div class="list-group list-group-flush custom-scrollbar" style="max-height: 200px; overflow-y: auto;">
                            <a href="{{ route('client.catalog', array_merge(request()->except('category', 'page'), ['page' => 1])) }}" class="list-group-item list-group-item-action border-0 px-1 {{ !request('category') ? 'text-primary fw-bold' : 'text-secondary' }}">Tất cả sản phẩm</a>
                            @foreach($categories as $category)
                                <a href="{{ route('client.catalog', array_merge(request()->except('page'), ['category' => $category->slug, 'page' => 1])) }}" class="list-group-item list-group-item-action border-0 px-1 d-flex category-item {{ request('category') == $category->slug ? 'text-primary fw-bold' : 'text-secondary' }}">
                                    <span>{{ $category->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-3">Thương hiệu</h5>
                        <input type="text" class="form-control border-0 bg-light mb-2" placeholder="Tìm thương hiệu..." id="brandSearch">
                        <div class="list-group list-group-flush custom-scrollbar" style="max-height: 200px; overflow-y: auto;">
                            <a href="{{ route('client.catalog', array_merge(request()->except('brand', 'page'), ['page' => 1])) }}" class="list-group-item list-group-item-action border-0 px-1 {{ !request('brand') ? 'text-primary fw-bold' : 'text-secondary' }}">Tất cả thương hiệu</a>
                            @foreach($brands as $brand)
                                <a href="{{ route('client.catalog', array_merge(request()->except('page'), ['brand' => $brand->slug, 'page' => 1])) }}" class="list-group-item list-group-item-action border-0 px-1 brand-item {{ request('brand') == $brand->slug ? 'text-primary fw-bold' : 'text-secondary' }}">
                                    <span>{{ $brand->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-9">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom">
                <h1 class="h3 fw-bold mb-3 mb-md-0">Sản phẩm</h1>
                <select class="form-select form-select-sm rounded-pill px-3 w-auto" id="sortSelect" onchange="window.location.href=this.value">
                    <option value="{{ route('client.catalog', array_merge(request()->query(), ['sort' => 'newest', 'page' => 1])) }}" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="{{ route('client.catalog', array_merge(request()->query(), ['sort' => 'price_asc', 'page' => 1])) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                    <option value="{{ route('client.catalog', array_merge(request()->query(), ['sort' => 'price_desc', 'page' => 1])) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao xuống Thấp</option>
                </select>
            </div>

            @if($products->isEmpty())
                <div class="alert alert-info text-center p-5 rounded-4 border-0 shadow-sm bg-white">
                    <h5 class="fw-bold">Không tìm thấy sản phẩm nào!</h5>
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
                                            <div class="product-image-placeholder"><i class="bi bi-image text-muted"></i></div>
                                        @endif
                                    </div>
                                </a>
                                <div class="card-body d-flex flex-column bg-white">
                                    <p class="text-muted small mb-1">{{ $product->category->name ?? 'Không phân loại' }}</p>
                                    <h3 class="h6 fw-bold mb-2">{{ $product->name }}</h3>
                                    <div class="mt-auto">
                                        <div class="fw-bold text-primary fs-5 mb-2">{{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}đ</div>
                                        
                                        <form action="{{ route('client.cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="btn btn-outline-primary w-100 rounded-pill fw-bold">
                                                <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center">{{ $products->links('pagination::bootstrap-5') }}</div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    .product-card { transition: transform 0.3s ease; }
    .product-card:hover { transform: translateY(-5px); }
    .product-image-container { position: relative; width: 100%; padding-top: 100%; background: #f8f9fa; }
    .product-image { position: absolute; top: 0; width: 100%; height: 100%; object-fit: contain; padding: 1rem; }
</style>
@endpush