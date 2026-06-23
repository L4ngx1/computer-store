@extends('layouts.site')

@section('title', 'Product Detail')

@section('content')
<div class="border-bottom sticky-top bg-white style-subnav" style="top: 0; z-index: 1020;">
    <div class="container d-flex justify-content-between align-items-center py-2">
        <ul class="nav gap-4 small fw-semibold">
            <li class="nav-item">
                <a class="nav-link p-0 text-dark border-bottom border-primary border-2 pb-2 active" href="#">About Product</a>
            </li>
            <li class="nav-item">
                <a class="nav-link p-0 text-secondary pb-2" href="#">Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link p-0 text-secondary pb-2" href="#">Specs</a>
            </li>
        </ul>
        <div class="d-flex align-items-center gap-3">
            <div class="small">
                <span class="text-secondary small">On Sale from</span>
                <span class="fw-bold text-dark d-block" style="font-size: 15px;">$3,299.00</span>
            </div>
            <div class="d-flex align-items-center bg-light border px-2 py-1 rounded">
                <span class="small text-secondary me-2">Qty:</span>
                <input type="number" value="1" min="1" class="form-control form-control-sm text-center border-0 bg-transparent p-0" style="width: 30px; font-weight: bold;">
            </div>
            <button class="btn rounded-pill text-white px-4 fw-semibold btn-sm" style="background-color: #0156FF;">
                Add to Cart
            </button>
            <button class="btn btn-warning rounded-pill px-3 fw-semibold btn-sm" style="background-color: #FFB800; border: none;">
                PayPal
            </button>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="small text-secondary mb-4" style="font-size: 12px;">
        Home <span class="mx-1">›</span> Laptops <span class="mx-1">›</span> <span class="text-dark">MSI Titan Series</span>
    </div>

    <div class="row g-5 align-items-center">
        <div class="col-lg-7">
            <h1 class="fw-bold mb-3" style="font-size: 32px; color: #000; line-height: 1.2;">
                MSI Titan GT77 HX 13VI
            </h1>
            <p class="text-primary small mb-4" style="color: #0156FF !important; cursor: pointer;">
                Be the first to review this product
            </p>
            
            <div class="pe-lg-5" style="font-size: 14px; line-height: 1.6; color: #000;">
                <p>MSI Titan GT77 HX is the pinnacle of gaming laptops. Equipped with the latest Intel® Core™ i9-13980HX processor and NVIDIA® GeForce RTX™ 4090 graphics, it delivers desktop-level performance for hardcore gamers and professional creators alike.</p>
                <p>Featuring a stunning 17.3" 4K Mini LED 144Hz display, Mechanical Cherry MX keyboard, and extreme cooling solution, the Titan GT77 HX redefines what is possible on a portable machine.</p>
            </div>

            <div class="mt-4">
                <span class="small fw-semibold text-secondary d-block mb-2">Color options:</span>
                <div class="d-flex gap-2">
                    <span class="d-inline-block rounded-circle border border-dark" style="width: 20px; height: 20px; background-color: #000; cursor: pointer;"></span>
                    <span class="d-inline-block rounded-circle" style="width: 20px; height: 20px; background-color: #cccccc; cursor: pointer;"></span>
                </div>
            </div>
        </div>

        <div class="col-lg-5 text-center">
            <div class="position-relative p-4 bg-white border-0">
                <div class="position-absolute top-0 end-0 d-flex flex-column gap-2 text-secondary" style="font-size: 16px;">
                    <a href="#" class="text-secondary text-decoration-none">📊</a>
                    <a href="#" class="text-secondary text-decoration-none">🤍</a>
                    <a href="#" class="text-secondary text-decoration-none">✉️</a>
                </div>

                <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600" 
                     class="img-fluid" 
                     style="max-height: 300px; object-fit: contain;" 
                     alt="MSI Titan Laptop">
                
                <div class="d-flex justify-content-center gap-2 mt-4">
                    <span class="d-inline-block rounded-circle bg-primary" style="width: 8px; height: 8px;"></span>
                    <span class="d-inline-block rounded-circle bg-light border" style="width: 8px; height: 8px; background-color: #E2E2E2;"></span>
                    <span class="d-inline-block rounded-circle bg-light border" style="width: 8px; height: 8px; background-color: #E2E2E2;"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 p-4 rounded text-center small text-secondary" style="background-color: #F5F7FF;">
        Have a question? <a href="#" class="text-primary fw-semibold text-decoration-none" style="color: #0156FF !important;">Contact Us</a> or call our experts at Shop Pty Ltd.
    </div>
</div>
@endsection