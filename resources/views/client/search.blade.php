@extends('layouts.site')

@section('title', 'Kết quả tìm kiếm')

@section('content')
<div class="container py-4">
    <div class="small text-secondary mb-3" style="font-size: 12px;">
        Trang chủ <span class="mx-1">›</span> <span class="text-dark">Kết quả tìm kiếm</span>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="fw-bold m-0" style="font-size: 24px; color: #000;">
                Kết quả tìm kiếm cho: <span class="text-secondary fw-normal">"MSI"</span>
            </h1>
            <p class="text-muted small m-0 mt-1">Tìm thấy 12 sản phẩm phù hợp</p>
        </div>
        
        <div class="d-flex align-items-center gap-2 small">
            <span class="text-secondary">Sắp xếp theo:</span>
            <select class="form-select form-select-sm bg-white border rounded-1" style="width: 150px; font-size: 13px;">
                <option>Sự phù hợp</option>
                <option>Giá: Thấp đến Cao</option>
                <option>Giá: Cao đến Thấp</option>
            </select>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-0 border-top border-start mb-5">
        @for($i = 1; $i <= 8; $i++)
        <div class="col border-end border-bottom p-3 bg-white position-relative style-product-card">
            <div class="d-flex align-items-center gap-1 mb-2">
                <span class="text-success" style="font-size: 11px;">● Còn hàng</span>
            </div>

            <div class="text-center my-3">
                <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400" 
                     class="img-fluid" 
                     style="max-height: 140px; object-fit: contain;" 
                     alt="Laptop">
            </div>

            <div class="text-warning small mb-1" style="font-size: 11px;">★★★★★ <span class="text-secondary">(4)</span></div>

            <h6 class="fw-normal mb-3 text-dark" style="font-size: 13px; line-height: 1.4; height: 36px; overflow: hidden;">
                MSI Cyborg 15 A12VF Intel i7 12650H, RTX 4060...
            </h6>

            <div class="mt-2">
                <div class="text-decoration-line-through text-muted small" style="font-size: 11px;">$1,499.00</div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark fs-5">$1,199.00</span>
                    <a href="{{ route('client.product') }}" class="btn btn-sm rounded-pill px-3 fw-semibold" style="border: 2px solid #0156FF; color: #0156FF; font-size: 12px;">
                        Chi tiết
                    </a>
                </div>
            </div>
        </div>
        @endfor
    </div>

    <nav class="mt-4">
        <ul class="pagination pagination-sm justify-content-center gap-1">
            <li class="page-item active"><span class="page-link rounded-circle border-0 text-dark bg-light fw-bold" style="width:30px; height:30px; text-align:center; padding:5px 0;">1</span></li>
            <li class="page-item"><a class="page-link rounded-circle border-0 text-secondary" style="width:30px; height:30px; text-align:center; padding:5px 0;" href="#">2</a></li>
            <li class="page-item"><a class="page-link rounded-circle border-0 text-secondary" style="width:30px; height:30px; text-align:center; padding:5px 0;" href="#">›</a></li>
        </ul>
    </nav>
</div>
@endsection