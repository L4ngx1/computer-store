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
                        <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}" class="main-product-image" id="mainProductImage">
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
                        <div class="product-thumbnail-item active" onclick="changeImage('{{ $product->thumbnail }}', this)">
                            <img src="{{ $product->thumbnail }}" alt="Thumbnail">
                        </div>
                    </div>
                    @endif
                    @foreach($product->images as $img)
                        <div class="col-auto">
                            <div class="product-thumbnail-item" onclick="changeImage('{{ $img->image_path }}', this)">
                                <img src="{{ $img->image_path }}" alt="Image">
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

    {{-- Phần đánh giá sản phẩm --}}
    <section id="reviews" class="bg-white p-4 p-md-5 rounded-4 shadow-sm mb-5" aria-labelledby="reviews-heading">
        <h2 id="reviews-heading" class="h4 fw-bold mb-4">Đánh giá sản phẩm</h2>

        <div class="row g-4 mb-4">
            {{-- Tổng quan điểm --}}
            <div class="col-12 col-md-4 text-center">
                <div class="display-4 fw-bold text-primary">{{ number_format($reviewStats['average'] ?? 0, 1) }}<span class="h5 text-muted">/5</span></div>
                <div class="fs-5 mb-2" aria-label="Điểm trung bình {{ $reviewStats['average'] ?? 0 }} trên 5">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="bi {{ $i <= round($reviewStats['average'] ?? 0) ? 'bi-star-fill text-warning' : 'bi-star text-secondary' }}"></i>
                    @endfor
                </div>
                <p class="text-muted mb-0">Dựa trên {{ $reviewStats['total'] ?? 0 }} đánh giá</p>

                {{-- Phân bố điểm --}}
                <div class="mt-3 text-start">
                    @for ($star = 5; $star >= 1; $star--)
                        @php
                            $count = $reviewStats['distribution'][$star] ?? 0;
                            $percent = ($reviewStats['total'] ?? 0) > 0 ? round($count / $reviewStats['total'] * 100) : 0;
                        @endphp
                        <div class="d-flex align-items-center gap-2 mb-1 small">
                            <span class="text-nowrap" style="width: 40px;">{{ $star }} <i class="bi bi-star-fill text-warning"></i></span>
                            <div class="progress flex-grow-1" style="height: 8px;" role="progressbar" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ $star }} sao: {{ $percent }}%">
                                <div class="progress-bar bg-warning" style="width: {{ $percent }}%"></div>
                            </div>
                            <span class="text-muted" style="width: 30px;">{{ $count }}</span>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Form gửi đánh giá --}}
            <div class="col-12 col-md-8">
                @auth
                    @if ($myReview)
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-1"></i>
                            Bạn đã đánh giá sản phẩm này.
                            @if (! $myReview->is_approved)
                                <span class="fw-semibold">Trạng thái: đang chờ kiểm duyệt.</span>
                            @else
                                <span class="fw-semibold">Trạng thái: đã duyệt hiển thị.</span>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('client.reviews.update', $myReview->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="my-rating">Điểm đánh giá của bạn</label>
                                <select class="form-select" id="my-rating" name="rating" required>
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ (int) old('rating', $myReview->rating) === $i ? 'selected' : '' }}>{{ $i }} sao</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="my-comment">Nội dung đánh giá</label>
                                <textarea class="form-control" id="my-comment" name="comment" rows="3" minlength="10" maxlength="2000" required>{{ old('comment', $myReview->comment) }}</textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary rounded-pill px-4">Cập nhật đánh giá</button>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('client.reviews.destroy', $myReview->id) }}" class="mt-2" onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger p-0 text-decoration-none">Xóa đánh giá</button>
                        </form>
                    @else
                        <h3 class="h5 fw-bold mb-3">Viết đánh giá của bạn</h3>
                        <form method="POST" action="{{ route('client.reviews.store', $product->id) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="rating">Điểm đánh giá <span class="text-danger">*</span></label>
                                <select class="form-select" id="rating" name="rating" required>
                                    <option value="">— Chọn số sao —</option>
                                    <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>5 sao — Rất hài lòng</option>
                                    <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 sao — Hài lòng</option>
                                    <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 sao — Bình thường</option>
                                    <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 sao — Không hài lòng</option>
                                    <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 sao — Rất tệ</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="comment">Nội dung đánh giá <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" minlength="10" maxlength="2000" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm (tối thiểu 10 ký tự)..." required>{{ old('comment') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-send me-1"></i> Gửi đánh giá
                            </button>
                        </form>
                    @endif
                @else
                    <div class="alert alert-light border text-center py-4 mb-0">
                        <i class="bi bi-chat-square-text display-6 text-secondary"></i>
                        <p class="mt-2 mb-3">Vui lòng <a href="{{ route('login.form') }}" class="fw-semibold">đăng nhập</a> để viết đánh giá cho sản phẩm này.</p>
                    </div>
                @endauth
            </div>
        </div>

        {{-- Danh sách đánh giá --}}
        <h3 class="h5 fw-bold mb-3 border-top pt-4">Nhận xét từ khách hàng</h3>
        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif

        @forelse ($reviews as $review)
            <article class="border-bottom pb-3 mb-3">
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <span class="fw-bold">{{ $review->user->name ?? 'Khách hàng' }}</span>
                    @php
                        $reviewAuthor = $review->user;
                        $verified = $reviewAuthor && $reviewAuthor->orders()->where('status', 'completed')->whereHas('items', fn ($q) => $q->where('product_id', $product->id))->exists();
                    @endphp
                    @if ($verified)
                        <span class="badge bg-success rounded-pill"><i class="bi bi-patch-check me-1"></i>Đã mua hàng</span>
                    @endif
                    <span class="text-muted small ms-auto">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="mb-1" aria-label="Đánh giá {{ $review->rating }} trên 5 sao">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill text-warning' : 'bi-star text-secondary' }}"></i>
                    @endfor
                </div>
                <p class="mb-0">{{ $review->comment }}</p>
            </article>
        @empty
            <div class="alert alert-light border text-center py-4">
                <i class="bi bi-chat-square display-6 text-secondary"></i>
                <p class="mt-2 mb-0">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên đánh giá!</p>
            </div>
        @endforelse

        <div class="d-flex justify-content-center mt-3">
            {{ $reviews->appends(['review_page' => $reviews->currentPage()])->fragment('reviews')->links() }}
        </div>
    </section>

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
                                    <img src="{{$related->thumbnail }}" class="product-image" alt="{{ $related->name }}">
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
