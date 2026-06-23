@extends('layouts.site')

@section('title', 'Computer Store - Home')

@section('content')

<section class="mb-5" style="background-color: #000; color: #fff;">
    <div class="container">
        <div class="row align-items-center m-0">
            <div class="col-lg-12 p-0">
                <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?w=1600&auto=format&fit=crop&q=80" 
                     class="img-fluid w-100" 
                     style="max-height: 400px; object-fit: cover;" 
                     alt="New Arrival Banner">
            </div>
        </div>
    </div>
</section>

<div class="container">
    <section class="mb-5">
        <div class="d-flex justify-content-center gap-3 text-center flex-wrap">
            <div class="p-3 border rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; cursor: pointer;">
                <span class="fw-semibold small" style="font-size: 12px;">Laptops</span>
            </div>
            <div class="p-3 border rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; cursor: pointer;">
                <span class="fw-semibold small" style="font-size: 12px;">PC Gaming</span>
            </div>
            <div class="p-3 border rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; cursor: pointer;">
                <span class="fw-semibold small" style="font-size: 12px;">Accessories</span>
            </div>
            <div class="p-3 border rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; cursor: pointer;">
                <span class="fw-semibold small" style="font-size: 12px;">Monitors</span>
            </div>
        </div>
    </section>

    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold" style="font-size: 22px; color: #000;">New Products</h2>
            <a href="{{ route('client.catalog') }}" class="text-decoration-underline small" style="color: #0156FF;">
                See All Products
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-0 border-top border-start">
            @for($i = 0; $i < 4; $i++)
            <div class="col border-end border-bottom p-3 bg-white position-relative style-product-card">
                <div class="d-flex align-items-center gap-1 mb-2">
                    <span class="text-success" style="font-size: 11px;">● in stock</span>
                </div>

                <div class="text-center my-3">
                    <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400" 
                         class="img-fluid" 
                         style="max-height: 150px; object-fit: contain;" 
                         alt="Product">
                </div>

                <div class="text-warning small mb-1" style="font-size: 11px;">★★★★★ <span class="text-secondary">(6)</span></div>

                <h6 class="fw-normal mb-3 text-dark" style="font-size: 13px; line-height: 1.4; height: 36px; overflow: hidden;">
                    EXOS PC Advanced Gaming Build AMD Ryzen 5, RTX 4060 Ti...
                </h6>

                <div class="mt-2">
                    <div class="text-decoration-line-through text-muted small" style="font-size: 12px;">$1,599.00</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark fs-5">$1,250.00</span>
                        <a href="{{ route('client.product') }}" class="btn btn-sm rounded-pill px-3 fw-semibold" style="border: 2px solid #0156FF; color: #0156FF; font-size: 12px;">
                            Details
                        </a>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </section>

    <section class="py-4 my-5 border-top border-bottom bg-light">
        <div class="row row-cols-2 row-cols-md-5 g-3 align-items-center text-center m-0 text-muted fw-bold" style="letter-spacing: 2px; font-size: 14px;">
            <div class="col opacity-50">ROCCAT</div>
            <div class="col opacity-50">MSI</div>
            <div class="col opacity-50">THERMALTAKE</div>
            <div class="col opacity-50">AORUS</div>
            <div class="col opacity-50">CORSAIR</div>
        </div>
    </section>
</div>

@endsection