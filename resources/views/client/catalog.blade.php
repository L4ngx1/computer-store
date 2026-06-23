@extends('layouts.site')

@section('title', 'MSI PS Series - Catalog')

@section('content')
<div class="container py-4">
    <div class="w-100 mb-4 text-white p-5 d-flex align-items-center justify-content-between position-relative overflow-hidden style-top-banner" 
         style="background: linear-gradient(90deg, #020813 0%, #0c1933 100%); min-height: 120px;">
        <div class="z-1">
            <span class="badge bg-primary text-uppercase mb-2" style="background-color: #0156FF !important;">ASUS TUF GAMING FX505</span>
            <h2 class="fw-bold m-0" style="font-size: 22px;">HIGH PERFORMANCE AT AN AFFORDABLE PRICE</h2>
        </div>
        <img src="https://images.unsplash.com/photo-1603481588273-2f908a9a7a1b?w=500" 
             alt="Banner Laptop" 
             class="position-absolute end-0 top-0 h-100 opacity-50" 
             style="object-fit: cover; width: 40%;">
    </div>

    <div class="small text-secondary mb-3" style="font-size: 12px;">
        Home <span class="mx-1">›</span> Laptops <span class="mx-1">›</span> <span class="text-dark">MSI PS Series</span>
    </div>
    <h1 class="fw-bold mb-4 text-dark" style="font-size: 26px;">MSI PS Series (20)</h1>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="border p-3 bg-white mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold text-dark small text-uppercase">Filters</span>
                    <button class="btn btn-sm btn-light border rounded-pill px-3" style="font-size: 11px;">Clear Filter</button>
                </div>

                <div class="mb-4">
                    <p class="fw-bold small text-dark mb-2 border-bottom pb-1">Category</p>
                    <ul class="list-unstyled small d-flex flex-column gap-2 text-secondary">
                        <li class="d-flex justify-content-between"><span>CUSTOM PC</span> <span class="badge bg-light text-dark border">15</span></li>
                        <li class="d-flex justify-content-between text-dark fw-semibold"><span>MSI ALL-IN-ONE PCS</span> <span class="badge bg-light text-dark border">45</span></li>
                        <li class="d-flex justify-content-between"><span>HP/COMPAQ PCS</span> <span class="badge bg-light text-dark border">1</span></li>
                    </ul>
                </div>

                <div class="mb-4">
                    <p class="fw-bold small text-dark mb-2 border-bottom pb-1">Price</p>
                    <ul class="list-unstyled small d-flex flex-column gap-2 text-secondary">
                        <li><a href="#" class="text-decoration-none text-secondary">$0.00 - $1,000.00</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary">$1,000.00 - $2,000.00</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary fw-semibold text-dark">$2,000.00 - $3,000.00</a></li>
                    </ul>
                </div>

                <div class="mb-3">
                    <p class="fw-bold small text-dark mb-2 border-bottom pb-1">Brands</p>
                    <div class="row row-cols-2 g-2 text-center small fw-bold text-muted opacity-50">
                        <div class="col border p-2">MSI</div>
                        <div class="col border p-2">ASUS</div>
                        <div class="col border p-2">HP</div>
                        <div class="col border p-2">GIGABYTE</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-dark text-white p-4 text-center rounded-1 position-relative overflow-hidden style-sidebar-ad" style="min-height: 250px;">
                <h5 class="fw-bold mb-2 text-warning">THE ICON SERIES</h5>
                <p class="small text-secondary">Become Iconic</p>
                <img src="https://images.unsplash.com/photo-1593305841991-05c297ba4575?w=300" class="w-100 position-absolute bottom-0 start-0 opacity-25" style="object-fit: cover; max-height: 150px;">
            </div>
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center bg-light p-2 border mb-4 rounded-1 small text-secondary">
                <div>Items 1-5 of 20</div>
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-1">
                        <span>Sort By:</span>
                        <select class="form-select form-select-sm border bg-white" style="font-size: 12px; width: 110px;">
                            <option>Position</option>
                            <option>Price</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column gap-3">
                @for($i = 1; $i <= 4; $i++)
                <div class="row g-0 border p-3 align-items-center bg-white style-product-row position-relative">
                    <span class="position-absolute text-success small" style="top: 15px; right: 20px; font-size: 11px;">● in stock</span>
                    
                    <div class="col-md-3 text-center">
                        <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400" 
                             class="img-fluid" 
                             style="max-height: 140px; object-fit: contain;" 
                             alt="MSI Laptop">
                        <div class="text-warning mt-2" style="font-size: 11px;">★★★★★ <span class="text-secondary small">(4)</span></div>
                    </div>

                    <div class="col-md-6 px-md-3 mt-3 mt-md-0">
                        <span class="text-muted small d-block" style="font-size: 11px;">SKU: DB551A</span>
                        <h5 class="fw-normal text-dark my-2" style="font-size: 14px; line-height: 1.5;">
                            MSI Creator Z16 Professional Laptop 16" QHD+ Intel Core i7-11800H, RTX 3060, 32GB RAM, 1TB NVMe SSD, Win 11 Pro
                        </h5>
                        
                        <div class="row g-1 mt-2 text-secondary" style="font-size: 11px;">
                            <div class="col-4 border-end"><strong>CPU</strong>: i7-11800H</div>
                            <div class="col-4 border-end"><strong>VGA</strong>: RTX 3060</div>
                            <div class="col-4"><strong>RAM</strong>: 32GB</div>
                        </div>
                    </div>

                    <div class="col-md-3 text-md-end text-start mt-3 mt-md-0 border-start ps-md-4">
                        <div class="text-decoration-line-through text-muted small" style="font-size: 12px;">$2,499.00</div>
                        <div class="fw-bold text-dark fs-4 mb-3">$1,999.00</div>
                        
                        <button class="btn btn-sm rounded-pill px-3 fw-semibold w-100 mb-2" style="border: 2px solid #0156FF; color: #0156FF; font-size: 12px;">
                            Add To Cart
                        </button>
                        
                        <div class="d-flex justify-content-md-end justify-content-start gap-2 text-secondary mt-2" style="font-size: 13px;">
                            <span style="cursor:pointer;">🤍</span>
                            <span style="cursor:pointer;">📊</span>
                        </div>
                    </div>
                </div>
                @endfor
            </div>

            <nav class="mt-4">
                <ul class="pagination pagination-sm justify-content-center gap-1">
                    <li class="page-item active"><span class="page-link rounded-circle border text-dark bg-light fw-bold" style="width:30px; height:30px; text-align:center; padding:5px 0;">1</span></li>
                    <li class="page-item"><a class="page-link rounded-circle border text-secondary" style="width:30px; height:30px; text-align:center; padding:5px 0;" href="#">2</a></li>
                    <li class="page-item"><a class="page-link rounded-circle border text-secondary" style="width:30px; height:30px; text-align:center; padding:5px 0;" href="#">›</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection