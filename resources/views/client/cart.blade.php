@extends('layouts.site')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-4">
    <div class="small text-secondary mb-3" style="font-size: 12px;">
        Home <span class="mx-1">›</span> Login <span class="mx-1">›</span> <span class="text-dark">Shopping Cart</span>
    </div>

    <h1 class="fw-bold mb-4" style="color: #000; font-size: 32px;">Shopping Cart</h1>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="row d-none d-md-flex pb-2 mb-2 border-bottom text-secondary fw-semibold" style="font-size: 13px;">
                <div class="col-md-6">Item</div>
                <div class="col-md-2 text-center">Price</div>
                <div class="col-md-2 text-center">Qty</div>
                <div class="col-md-2 text-end">Subtotal</div>
            </div>

            @for($i = 1; $i <= 2; $i++)
            <div class="row align-items-center py-3 border-bottom position-relative">
                <div class="col-12 col-md-6 d-flex align-items-center mb-3 mb-md-0">
                    <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=300" 
                         class="img-fluid me-3" 
                         style="width: 100px; object-fit: contain;" 
                         alt="Product Image">
                    <div>
                        <h6 class="mb-1 fw-normal" style="font-size: 14px; line-height: 1.4;">
                            MSI MEG Trident X 10SD-1012AU Intel i7 10700K, 2070 SUPER, 32GB RAM...
                        </h6>
                    </div>
                </div>

                <div class="col-4 col-md-2 text-md-center fw-bold" style="font-size: 14px;">
                    $4,349.00
                </div>

                <div class="col-4 col-md-2 d-flex justify-content-center">
                    <input type="number" class="form-control text-center bg-light border-0" value="1" min="1" style="width: 60px; font-size: 14px;">
                </div>

                <div class="col-4 col-md-2 text-end fw-bold d-flex align-items-center justify-content-end" style="font-size: 14px;">
                    <span class="me-3">$13,047.00</span>
                    <div class="d-flex flex-column gap-1">
                        <a href="#" class="text-secondary text-decoration-none small border rounded-circle d-flex align-items-center justify-content-center" style="width: 20px; height: 20px; font-size: 10px;">✕</a>
                        <a href="#" class="text-secondary text-decoration-none small border rounded-circle d-flex align-items-center justify-content-center" style="width: 20px; height: 20px; font-size: 10px;">✏️</a>
                    </div>
                </div>
            </div>
            @endfor

            <div class="d-flex flex-wrap gap-2 justify-content-between mt-4">
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary rounded-pill px-4 fw-semibold" style="font-size: 14px; color: #A2A6B0; border-color: #A2A6B0;">Continue Shopping</button>
                    <button class="btn btn-dark rounded-pill px-4 fw-semibold" style="font-size: 14px; background-color: #000;">Clear Shopping Cart</button>
                </div>
                <button class="btn btn-dark rounded-pill px-4 fw-semibold" style="font-size: 14px; background-color: #000;">Update Shopping Cart</button>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="p-4 rounded-3" style="background-color: #F5F7FF;">
                <h4 class="fw-bold mb-3" style="font-size: 24px; color: #000;">Summary</h4>
                
                <div class="border-bottom py-2 mb-2">
                    <div class="d-flex justify-content-between align-items-center small fw-semibold text-secondary" style="cursor: pointer;">
                        <span>Estimate Shipping and Tax</span>
                        <span>▼</span>
                    </div>
                </div>

                <div class="border-bottom py-2 mb-4">
                    <div class="d-flex justify-content-between align-items-center small fw-semibold text-secondary" style="cursor: pointer;">
                        <span>Apply Discount Code</span>
                        <span>▼</span>
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-2 small fw-semibold">
                    <span class="text-secondary">Subtotal</span>
                    <span>$13,047.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2 small fw-semibold">
                    <span class="text-secondary">Shipping</span>
                    <span>$21.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2" style="font-size: 12px; color: #A2A6B0;">
                    <span>Tax</span>
                    <span>$1.91</span>
                </div>
                <div class="d-flex justify-content-between mb-3" style="font-size: 12px; color: #A2A6B0;">
                    <span>GST (10%)</span>
                    <span>$1.91</span>
                </div>

                <hr style="border-color: #A2A6B0;">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold" style="font-size: 16px;">Order Total</span>
                    <span class="fw-bold" style="font-size: 20px; color: #000;">$13,068.00</span>
                </div>

                <button class="btn w-100 rounded-pill py-2 text-white fw-semibold mb-2" style="background-color: #0156FF; font-size: 14px;">
                    Proceed to Checkout
                </button>
                
                <button class="btn w-100 rounded-pill py-2 fw-semibold mb-2" style="background-color: #FFB800; color: #232733; font-size: 14px;">
                    Check out with <span class="fw-bold text-italic">PayPal</span>
                </button>

                <button class="btn btn-outline-secondary w-100 rounded-pill py-2 fw-semibold border-0 text-secondary" style="font-size: 13px;">
                    Check Out with Multiple Addresses
                </button>
            </div>
        </div>
    </div>
</div>
@endsection