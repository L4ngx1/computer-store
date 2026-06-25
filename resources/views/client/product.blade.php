@extends('layouts.site')

@section('title', $product->name)

@section('content')
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('client.catalog') }}" class="text-decoration-none">Danh mục</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('client.catalog', ['category' => $product->category->slug]) }}" class="text-decoration-none">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5 mb-5">
        <div class="col-12 col-md-6">
            <div class="card border-0 rounded-4 overflow-hidden shadow-sm bg-white mb-3">
                <div class="card-body p-0 text-center position-relative">
                    @if($product->sale_price)
                        <span class="position-absolute top-0 start-0 badge bg-danger m-3 px-3 py-2 rounded-pill shadow-sm fs-6 z-1">
                            Sale {{ round(((($product->price - $product->sale_price) / $product->price) * 100)) }}%
                        </span>
                    @endif
                    @if($product->thumbnail)
                        <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}" class="main-product-image" id="mainProductImage">
                    @else
                        <div class="main-product-image-placeholder">
                            <i class="bi bi-image text-muted"></i>
                        </div>
                    @endif
                </div>
            </div>

            @if($product->images->count() > 0 || $product->thumbnail)
                <div class="row g-2" id="thumbnail-gallery">
                    @if($product->thumbnail)
                    <div class="col-auto">
                        <div class="product-thumbnail-item active" onclick="changeImage('{{ Storage::url($product->thumbnail) }}', this)">
                            <img src="{{ Storage::url($product->thumbnail) }}" alt="Thumbnail">
                        </div>
                    </div>
                    @endif
                    @foreach($product->images as $img)
                        <div class="col-auto">
                            <div class="product-thumbnail-item" onclick="changeImage('{{ Storage::url($img->image_path) }}', this)">
                                <img src="{{ Storage::url($img->image_path) }}" alt="Image">
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="col-12 col-md-6">
            <h1 class="h2 fw-bold mb-3">{{ $product->name }}</h1>

            <div class="d-flex align-items-center gap-3 mb-4 small text-secondary flex-wrap">
                <div>Thương hiệu: <a href="{{ route('client.catalog', ['brand' => $product->brand->slug ?? '']) }}" class="fw-bold text-decoration-none">{{ $product->brand->name ?? 'Đang cập nhật' }}</a></div>
                <div class="vr"></div>
                <div>SKU: <span class="fw-bold text-dark">{{ $product->sku ?? 'N/A' }}</span></div>
                <div class="vr"></div>
                <div>Tình trạng:
                    @if($product->stock > 0)
                        <span class="badge bg-success rounded-pill px-2">Còn hàng ({{ $product->stock }})</span>
                    @else
                        <span class="badge bg-danger rounded-pill px-2">Hết hàng</span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                @if($product->sale_price)
                    <div class="d-flex align-items-end gap-3">
                        <div class="display-5 fw-bold text-danger">{{ number_format($product->sale_price, 0, ',', '.') }}đ</div>
                        <div class="h4 text-muted text-decoration-line-through mb-1">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                    </div>
                @else
                    <div class="display-5 fw-bold text-primary">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                @endif
            </div>

            <div class="card border-0 bg-light rounded-4 mb-4">
                <div class="card-body p-4">
                    <p class="mb-0 text-secondary lh-lg">{{ $product->summary ?? 'Chưa có mô tả ngắn gọn cho sản phẩm này.' }}</p>
                </div>
            </div>

            <div class="d-flex gap-3 mb-5">
                <form action="{{ route('client.cart.add', $product->id) }}" method="POST" class="flex-grow-1">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ
                    </button>
                </form>
                <button type="button" class="btn btn-outline-danger btn-lg rounded-pill px-4" aria-label="Yêu thích">
                    <i class="bi bi-heart"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <ul class="nav nav-pills mb-4 border-bottom pb-3 gap-2" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill fw-bold px-4" id="pills-desc-tab" data-bs-toggle="pill" data-bs-target="#pills-desc" type="button" role="tab" aria-controls="pills-desc" aria-selected="true">Mô tả chi tiết</button>
                </li>
                @if($product->attributes->count() > 0)
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill fw-bold px-4" id="pills-attr-tab" data-bs-toggle="pill" data-bs-target="#pills-attr" type="button" role="tab" aria-controls="pills-attr" aria-selected="false">Thông số kỹ thuật</button>
                </li>
                @endif
            </ul>
            <div class="tab-content bg-white p-4 rounded-4 shadow-sm" id="pills-tabContent">
                <div class="tab-pane fade show active lh-lg" id="pills-desc" role="tabpanel">
                    {!! $product->description ?? 'Chưa có mô tả chi tiết cho sản phẩm này.' !!}
                </div>
                @if($product->attributes->count() > 0)
                <div class="tab-pane fade" id="pills-attr" role="tabpanel">
                    <table class="table table-striped table-hover border mb-0">
                        <tbody>
                            @foreach($product->attributes as $attr)
                            <tr>
                                <th class="w-25 bg-light">{{ $attr->name }}</th>
                                <td>{{ $attr->value }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($relatedProducts->count() > 0)
    <div class="mb-5">
        <h2 class="h4 fw-bold mb-4 border-bottom pb-2">Sản phẩm liên quan</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($relatedProducts as $related)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 product-card overflow-hidden">
                        <a href="{{ route('client.product', $related->slug) }}" class="text-decoration-none text-dark">
                            <div class="product-image-container">
                                @if($related->thumbnail)
                                    <img src="{{ Storage::url($related->thumbnail) }}" class="product-image" alt="{{ $related->name }}">
                                @else
                                    <div class="product-image-placeholder">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column bg-white">
                                <h3 class="h6 fw-bold mb-2 text-truncate-2">{{ $related->name }}</h3>
                                <div class="mt-auto">
                                    <div class="fw-bold text-primary fs-5 mb-2">{{ number_format($related->sale_price ?? $related->price, 0, ',', '.') }}đ</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<script>
    function changeImage(src, element) {
        document.getElementById('mainProductImage').src = src;
        document.querySelectorAll('.product-thumbnail-item').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }
</script>
@endpush

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

    .main-product-image {
        width: 100%;
        height: 450px;
        object-fit: contain;
        padding: 1rem;
    }
    .main-product-image-placeholder {
        width: 100%;
        height: 450px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        background-color: #f8f9fa;
    }
    .product-thumbnail-item {
        width: 80px;
        height: 80px;
        border: 2px solid transparent;
        border-radius: 0.5rem;
        overflow: hidden;
        cursor: pointer;
        transition: border-color 0.2s ease;
    }
    .product-thumbnail-item.active,
    .product-thumbnail-item:hover { border-color: var(--bs-primary); }
    .product-thumbnail-item img { width: 100%; height: 100%; object-fit: cover; }

    .product-image-container {
        position: relative;
        width: 100%;
        padding-top: 100%;
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
    .product-card:hover .product-image { transform: scale(1.05); }
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
