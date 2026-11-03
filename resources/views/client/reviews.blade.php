@extends('layouts.site')

@section('title', 'Đánh giá của tôi')

@section('content')
    <div class="small text-secondary mb-4">Trang chủ › <a href="{{ route('client.account') }}" class="text-decoration-none">Tài khoản</a> › Đánh giá của tôi</div>

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h2 fw-bold mb-0">Đánh giá của tôi</h1>
        <a href="{{ route('client.account') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Về tài khoản
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif

    @if ($reviews->isEmpty())
        <div class="text-center py-5 bg-light rounded-4">
            <i class="bi bi-chat-square-text display-4 text-secondary"></i>
            <p class="lead mt-3">Bạn chưa viết đánh giá nào.</p>
            <a href="{{ route('client.catalog') }}" class="btn btn-primary rounded-pill px-4">Xem sản phẩm</a>
        </div>
    @else
        <div class="d-grid gap-3">
            @foreach ($reviews as $review)
                <article class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                            <div>
                                <a href="{{ route('client.product', $review->product->slug ?? '#') }}" class="fw-bold text-decoration-none">
                                    {{ $review->product->name ?? 'Sản phẩm đã gỡ' }}
                                </a>
                                <div class="mt-1" aria-label="Đánh giá {{ $review->rating }} trên 5 sao">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill text-warning' : 'bi-star text-secondary' }}"></i>
                                    @endfor
                                    <span class="text-muted small ms-2">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                            <span class="badge {{ $review->is_approved ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill">
                                {{ $review->is_approved ? 'Đã duyệt' : 'Chờ kiểm duyệt' }}
                            </span>
                        </div>
                        <p class="mb-3 mt-2">{{ $review->comment }}</p>

                        <div class="d-flex gap-2">
                            <a href="{{ route('client.product', $review->product->slug ?? '#') }}#reviews" class="btn btn-sm btn-outline-primary rounded-pill">Xem sản phẩm</a>
                            <form method="POST" action="{{ route('client.reviews.destroy', $review->id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Xóa</button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $reviews->links() }}
        </div>
    @endif
@endsection
