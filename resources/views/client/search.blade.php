@extends('layouts.site')

@section('title', 'Tìm kiếm sản phẩm')

@section('content')
    <div class="row justify-content-center mb-5">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="text-center mb-4">
                <h1 class="fw-bold">Tìm kiếm</h1>
                <p class="text-secondary">Tìm kiếm sản phẩm theo tên, mã SKU hoặc mô tả ngắn.</p>
            </div>
            <form action="{{ route('client.search') }}" method="GET" class="d-flex shadow-sm rounded-pill p-1 bg-white">
                <input type="text" name="q" class="form-control border-0 rounded-pill px-4" placeholder="Nhập từ khóa tìm kiếm..." value="{{ request('q') }}" required>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Tìm kiếm</button>
            </form>
        </div>
    </div>

    @if($keyword)
        <div class="mb-4 border-bottom pb-3">
            <h5 class="fw-bold">Kết quả tìm kiếm cho: <span class="text-primary">"{{ $keyword }}"</span></h5>
            <div class="text-secondary small">{{ $products->total() }} sản phẩm được tìm thấy.</div>
        </div>

        @if($products->isEmpty())
            <div class="alert alert-info text-center p-5 rounded-4 border-0 shadow-sm bg-white">
                <i class="bi bi-search display-4 text-secondary mb-3"></i>
                <h5 class="fw-bold">Không có kết quả!</h5>
                <p class="text-secondary mb-0">Rất tiếc, chúng tôi không tìm thấy sản phẩm nào phù hợp với từ khóa của bạn.</p>
            </div>
        @else
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
                @foreach($products as $product)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-4 product-card overflow-hidden">
                            <a href="{{ route('client.product', $product->slug) }}" class="text-decoration-none text-dark d-flex flex-column h-100">
                                <div class="product-image-container">
                                    @if($product->thumbnail)
                                        <img src="{{ Storage::url($product->thumbnail) }}" class="card-img-top" alt="{{ $product->name }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center h-100 w-100">
                                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                @if($product->sale_price)
                                    <span class="position-absolute top-0 start-0 badge bg-danger m-2">
                                        -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                    </span>
                                @endif
                                <div class="card-body d-flex flex-column product-card-body">
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
                                        <form action="{{ route('client.cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-sm btn-outline-primary w-100 rounded-pill fw-bold">
                                                <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ
                                            </button>
                                        </form>
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
    @endif
@endsection


