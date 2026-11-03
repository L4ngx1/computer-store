@extends('layouts.site')

@section('title', 'Danh sách yêu thích')

@section('content')
    <div class="small text-secondary mb-4">Trang chủ › <a href="{{ route('client.account') }}" class="text-decoration-none">Tài khoản</a> › Danh sách yêu thích</div>

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h2 fw-bold mb-0">
            <i class="bi bi-heart-fill text-danger me-2"></i> Danh sách yêu thích
        </h1>
        <a href="{{ route('client.account') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Về tài khoản
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="status">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($wishlists->isEmpty())
        <div class="text-center py-5 bg-light rounded-4">
            <i class="bi bi-heart display-4 text-secondary"></i>
            <p class="lead mt-3">Bạn chưa có sản phẩm yêu thích nào.</p>
            <a href="{{ route('client.catalog') }}" class="btn btn-primary rounded-pill px-4">Xem sản phẩm</a>
        </div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach ($wishlists as $wishlist)
                @php $product = $wishlist->product; @endphp
                @if ($product)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-4 product-card overflow-hidden position-relative">
                            {{-- Nút xóa khỏi wishlist --}}
                            <form method="POST" action="{{ route('client.wishlist.toggle', $product) }}" class="position-absolute top-0 end-0 m-2" style="z-index: 10;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger rounded-circle p-2" aria-label="Xóa khỏi yêu thích" title="Xóa khỏi yêu thích">
                                    <i class="bi bi-heart-fill"></i>
                                </button>
                            </form>

                            <a href="{{ route('client.product', $product->slug) }}" class="text-decoration-none text-dark">
                                <div class="product-image-container">
                                    @if ($product->thumbnail)
                                        <img src="{{ $product->thumbnail }}" class="product-image" loading="lazy" decoding="async" alt="{{ $product->name }}">
                                    @else
                                        <div class="product-image-placeholder">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column bg-white">
                                    <h3 class="h6 fw-bold mb-2 text-truncate-2">{{ $product->name }}</h3>
                                    <div class="mt-auto">
                                        @if ($product->sale_price)
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <span class="fw-bold text-danger fs-5">{{ number_format($product->sale_price, 0, ',', '.') }}đ</span>
                                                <span class="text-muted text-decoration-line-through small">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                            </div>
                                        @else
                                            <div class="fw-bold text-primary fs-5 mb-2">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                                        @endif
                                        <form action="{{ route('client.cart.add', $product->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary w-100 rounded-pill fw-bold" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                                <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $wishlists->links('pagination::bootstrap-5') }}
        </div>
    @endif
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
    .product-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important; }
    .product-image-container {
        position: relative;
        width: 100%;
        padding-top: 100%;
        overflow: hidden;
        background-color: #f8f9fa;
    }
    .product-image {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        object-fit: contain;
        padding: 1rem;
    }
    .product-image-placeholder {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
    }
</style>
@endpush
