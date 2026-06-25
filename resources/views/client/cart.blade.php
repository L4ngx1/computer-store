@extends('layouts.site')

@section('title', 'Giỏ hàng')

@section('content')
<div class="my-5">
    <h1 class="h2 fw-bold mb-4">Giỏ hàng của bạn</h1>

    @if(session('cart_success'))
        <div class="alert alert-success" role="alert">{{ session('cart_success') }}</div>
    @endif
    @if(session('cart_error'))
        <div class="alert alert-danger" role="alert">{{ session('cart_error') }}</div>
    @endif
    @if(session('checkout_success'))
        <div class="alert alert-success" role="alert">{{ session('checkout_success') }}</div>
    @endif

    @if($cartItems->isEmpty())
        <div class="text-center py-5 bg-light rounded-4">
            <i class="bi bi-cart-x" style="font-size: 4rem;"></i>
            <p class="lead mt-3">Giỏ hàng của bạn đang trống.</p>
            <a href="{{ route('client.catalog') }}" class="btn btn-primary rounded-pill px-4">Tiếp tục mua sắm</a>
        </div>
    @else
        <div class="row g-5">
            <div class="col-lg-8">
                <form action="{{ route('client.cart.update') }}" method="POST" id="cart-form">
                    @csrf
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 mb-0">Có {{ $cartItems->count() }} sản phẩm trong giỏ</h2>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmClearCart()">
                            <i class="bi bi-trash me-1"></i> Xóa tất cả
                        </button>
                    </div>

                    <div class="list-group list-group-flush border rounded-4">
                        @foreach($cartItems as $item)
                            <div class="list-group-item px-4 py-3">
                                <div class="row align-items-center g-3">
                                    <div class="col-12 col-md-2 text-center">
                                        <a href="{{ route('client.product', $item->product->slug) }}">
                                            <img src="{{ $item->product->thumbnail ? $item->product->thumbnail : 'https://via.placeholder.com/100' }}" alt="{{ $item->product->name }}" class="img-fluid rounded" style="max-height: 80px; object-fit: contain;">
                                        </a>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <a href="{{ route('client.product', $item->product->slug) }}" class="text-dark fw-bold text-decoration-none">{{ $item->product->name }}</a>
                                        <p class="small text-muted mb-0">SKU: {{ $item->product->sku }}</p>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <span class="fw-bold">{{ number_format($item->product->sale_price ?? $item->product->price, 0, ',', '.') }}đ</span>
                                        @if($item->product->sale_price)
                                            <small class="text-muted text-decoration-line-through d-block">{{ number_format($item->product->price, 0, ',', '.') }}đ</small>
                                        @endif
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button class="btn btn-outline-secondary" type="button" onclick="this.nextElementSibling.stepDown(); document.getElementById('cart-form').submit();">-</button>
                                            <input type="number" name="quantities[{{ $item->product->id }}]" class="form-control text-center" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" onchange="document.getElementById('cart-form').submit()">
                                            <button class="btn btn-outline-secondary" type="button" onclick="this.previousElementSibling.stepUp(); document.getElementById('cart-form').submit();">+</button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2 text-md-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="confirmRemoveItem({{ $item->product->id }})" title="Xóa sản phẩm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>

                {{-- Hidden forms for remove and clear actions --}}
                <form id="remove-item-form" action="" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
                <form id="clear-cart-form" action="{{ route('client.cart.clear') }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 2rem;">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-4">Tổng cộng</h2>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính</span>
                            <span class="fw-bold">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Phí vận chuyển</span>
                            <span class="fw-bold">Miễn phí</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                            <span>Tổng tiền</span>
                            <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ Auth::check() ? route('client.checkout') : route('login.form', ['redirect' => 'checkout']) }}" class="btn btn-primary btn-lg rounded-pill fw-bold">Tiến hành thanh toán</a>
                            <a href="{{ route('client.catalog') }}" class="btn btn-outline-secondary rounded-pill">Tiếp tục mua sắm</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function confirmRemoveItem(productId) {
        if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            const form = document.getElementById('remove-item-form');
            let url = '{{ route("client.cart.remove", ["id" => "DUMMY_ID"]) }}';
            form.action = url.replace('DUMMY_ID', productId);
            form.submit();
        }
    }

    function confirmClearCart() {
        if (confirm('Bạn có chắc muốn xóa toàn bộ sản phẩm trong giỏ hàng?')) {
            document.getElementById('clear-cart-form').submit();
        }
    }
</script>
@endpush