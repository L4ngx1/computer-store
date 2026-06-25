<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cửa hàng máy tính')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @stack('styles')
</head>
<body class="min-vh-100 d-flex flex-column">
    <div class="bg-black text-white small py-1">
        <div class="container-xxl">
            <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-2">
                <div class="dropdown">
                    <button class="btn btn-sm border-0 text-white p-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="text-white-50">Thứ Hai - Thứ Năm:</span> <strong>9:00 - 17:30</strong>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark small">
                        <li><span class="dropdown-item-text">Thứ Hai - Thứ Năm: 9:00 - 17:30</span></li>
                        <li><span class="dropdown-item-text">Thứ Sáu: 9:00 - 18:00</span></li>
                        <li><span class="dropdown-item-text">Thứ Bảy: 11:00 - 17:00</span></li>
                        <li><span class="dropdown-item-text">Chủ Nhật: Nghỉ</span></li>
                    </ul>
                </div>
                <div class="text-center text-white-50">Ghé showroom tại 1234 Street Address City Address, 1234 <a class="text-white fw-bold" href="{{ route('client.contact') }}">Liên hệ</a></div>
                <div class="d-flex align-items-center gap-3">
                    <span><strong>Gọi:</strong> (00) 1234 5678</span>
                    <a class="text-white" href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a class="text-white" href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-xl bg-white border-bottom py-2">
        <div class="container-xxl">
            <a class="navbar-brand me-xl-5" href="{{ route('home') }}" aria-label="Trang chủ">
                <span class="badge text-bg-primary rounded-3 fs-5 p-2"><i class="bi bi-layers-fill"></i></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#siteNavbar" aria-controls="siteNavbar" aria-expanded="false" aria-label="Mở menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="siteNavbar">
                <ul class="navbar-nav align-items-xl-center gap-xl-1 me-xl-auto mb-3 mb-xl-0">
                    <li class="nav-item"><a class="nav-link small fw-bold text-dark" href="{{ route('client.catalog', ['category' => 'laptop']) }}">Laptop</a></li>
                    <li class="nav-item"><a class="nav-link small fw-bold text-dark" href="{{ route('client.catalog', ['category' => 'pc-dong-bo']) }}">PC đồng bộ</a></li>
                    <li class="nav-item"><a class="nav-link small fw-bold text-dark" href="{{ route('client.catalog', ['category' => 'pc-gaming']) }}">PC gaming</a></li>
                    <li class="nav-item"><a class="nav-link small fw-bold text-dark" href="{{ route('client.catalog', ['category' => 'thiet-bi-mang']) }}">Thiết bị mạng</a></li>
                    <li class="nav-item"><a class="nav-link small fw-bold text-dark" href="{{ route('client.catalog', ['category' => 'linh-kien']) }}">Linh kiện PC</a></li>
                    <li class="nav-item"><a class="nav-link small fw-bold text-dark" href="{{ route('client.catalog') }}">Sản phẩm khác</a></li>
                    <li class="nav-item"><a class="nav-link small fw-bold text-dark" href="{{ route('client.contact') }}">Hỗ trợ</a></li>
                    <li class="nav-item ms-xl-2"><a class="btn btn-sm btn-outline-primary rounded-pill fw-bold px-4" href="{{ route('client.catalog') }}">Ưu đãi</a></li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('client.search') }}" method="GET" class="d-none d-lg-flex align-items-center bg-light rounded-pill px-2 border">
                        <input type="text" name="q" class="form-control border-0 bg-transparent shadow-none py-1" placeholder="Tìm sản phẩm..." value="{{ request('q') }}" style="width: 150px; font-size: 0.9rem;" required>
                        <button type="submit" class="btn border-0 p-1 text-primary"><i class="bi bi-search"></i></button>
                    </form>
                    <a href="{{ route('client.search') }}" class="btn border-0 fs-5 p-2 d-lg-none" aria-label="Tìm kiếm"><i class="bi bi-search"></i></a>
                    <a class="btn border-0 fs-5 p-2 position-relative" href="{{ route('client.cart') }}" aria-label="Giỏ hàng">
                        <span class="d-inline-block position-relative">
                            <i class="bi bi-cart3"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary p-1">2</span>
                        </span>
                    </a>
                    @auth
                        <a class="btn border-0 fs-4 text-primary p-2" href="{{ route('client.account') }}" aria-label="Tài khoản"><i class="bi bi-person-circle"></i></a>
                    @else
                        <a class="btn border-0 fs-4 text-primary p-2" href="{{ route('login.form') }}" aria-label="Tài khoản"><i class="bi bi-person-circle"></i></a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 py-4">
        <div class="container-xxl">
            @yield('content')
        </div>
    </main>

    <section class="bg-light py-4">
        <div class="container-xxl">
            <div class="row g-4 text-center justify-content-center">
                <div class="col-12 col-md-4">
                    <span class="badge text-bg-primary rounded-circle fs-4 p-3 mb-3"><i class="bi bi-headset"></i></span>
                    <h2 class="h6 fw-bold mb-2">Hỗ trợ sản phẩm</h2>
                    <p class="text-secondary small mb-0 mx-auto w-75">Bảo hành tận nơi đến 3 năm, giúp bạn yên tâm khi sử dụng.</p>
                </div>
                <div class="col-12 col-md-4">
                    <span class="badge text-bg-primary rounded-circle fs-4 p-3 mb-3"><i class="bi bi-person-circle"></i></span>
                    <h2 class="h6 fw-bold mb-2">Tài khoản cá nhân</h2>
                    <p class="text-secondary small mb-0 mx-auto w-75">Nhận ưu đãi lớn, giao hàng miễn phí và hỗ trợ riêng cho thành viên.</p>
                </div>
                <div class="col-12 col-md-4">
                    <span class="badge text-bg-primary rounded-circle fs-4 p-3 mb-3"><i class="bi bi-tags-fill"></i></span>
                    <h2 class="h6 fw-bold mb-2">Tiết kiệm hấp dẫn</h2>
                    <p class="text-secondary small mb-0 mx-auto w-75">Giảm đến 70% cho sản phẩm mới, luôn có mức giá tốt.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-black text-white mt-auto py-4 small">
        <div class="container-xxl">
            <div class="row gy-3 mb-4">
                <div class="col-6 col-lg">
                    <div class="text-white-50 fw-bold mb-2 small">Thông tin</div>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.about') }}">Về chúng tôi</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.contact') }}">Liên hệ</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.faq') }}">Chính sách bảo mật</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.search') }}">Tìm kiếm</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.faq') }}">Điều khoản</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.account') }}">Đơn hàng & đổi trả</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.contact') }}">Hỗ trợ</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.search') }}">Tìm kiếm nâng cao</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg">
                    <div class="text-white-50 fw-bold mb-2 small">Linh kiện PC</div>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'cpu']) }}">CPU</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'linh-kien']) }}">Card mở rộng</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'ssd-hdd']) }}">Ổ cứng trong</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'vga']) }}">Card đồ họa</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'linh-kien']) }}">Bàn phím / Chuột</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'linh-kien']) }}">Nguồn / Vỏ máy / Tản nhiệt</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'ram']) }}">RAM</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'linh-kien']) }}">Tai nghe / Loa</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg">
                    <div class="text-white-50 fw-bold mb-2 small">Máy tính đồng bộ</div>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'pc-dong-bo']) }}">PC lắp sẵn</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'pc-workstation']) }}">Máy chủ</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'pc-dong-bo', 'brand' => 'msi']) }}">MSI All-In-One</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'pc-dong-bo', 'brand' => 'hp']) }}">PC HP/Compaq</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'pc-dong-bo', 'brand' => 'asus']) }}">PC ASUS</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'pc-dong-bo']) }}">PC Tecs</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg">
                    <div class="text-white-50 fw-bold mb-2 small">Laptop</div>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'laptop-van-phong']) }}">Laptop dùng hằng ngày</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'laptop-do-hoa', 'brand' => 'msi']) }}">MSI Workstation</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'laptop', 'brand' => 'msi']) }}">MSI Prestige</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'laptop']) }}">Máy tính bảng & Pad</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'laptop']) }}">Netbook</a></li>
                        <li class="mb-1"><a class="link-light text-decoration-none" href="{{ route('client.catalog', ['category' => 'laptop-gaming']) }}">Laptop Gaming</a></li>
                    </ul>
                </div>
                <div class="col-12 col-lg">
                    <div class="text-white-50 fw-bold mb-2 small">Địa chỉ</div>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1">Địa chỉ: 1234 Street Address City Address, 1234</li>
                        <li class="mb-1">Điện thoại: <a class="link-info text-decoration-none" href="tel:0012345678">(00) 1234 5678</a></li>
                        <li class="mb-1">Thứ Hai - Thứ Năm: 9:00 - 17:30</li>
                        <li class="mb-1">Thứ Sáu: 9:00 - 18:00</li>
                        <li class="mb-1">Thứ Bảy: 11:00 - 17:00</li>
                        <li class="mb-1">Email: <a class="link-info text-decoration-none" href="mailto:shop@email.com">shop@email.com</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-top border-secondary pt-1 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-white-50 small">
                <div class="d-flex gap-3 fs-10">
                    <a class="text-white-50" href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a class="text-white-50" href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                </div>
                <div>Copyright © 2026 Shop Pty. Ltd.</div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
