@extends('layouts.site')

@section('title', 'Liên Hệ')

@section('content')

    <!-- Banner -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h1 class="fw-bold display-5">Liên Hệ</h1>
            <p class="text-muted mt-3">
                Chúng tôi luôn sẵn sàng lắng nghe bạn. Hãy liên hệ khi bạn cần hỗ trợ.
            </p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-5">
 
                <!-- Contact Info -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h3 class="fw-bold mb-4">Thông Tin Liên Hệ</h3>

                            <div class="d-flex align-items-start mb-4">
                                <i class="bi bi-geo-alt-fill fs-4 text-primary me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Địa chỉ</h6>
                                    <p class="text-muted mb-0">175 Tây Sơn, Đống Đa, Hà Nội</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-4">
                                <i class="bi bi-telephone-fill fs-4 text-primary me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Điện thoại</h6>
                                    <p class="text-muted mb-0">(+84) 0364 939 939</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-4">
                                <i class="bi bi-envelope-fill fs-4 text-primary me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Email</h6>
                                    <p class="text-muted mb-0">support@shop.com</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <i class="bi bi-clock-fill fs-4 text-primary me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Giờ làm việc</h6>
                                    <p class="text-muted mb-0">Thứ 2 - Chủ Nhật: 8:00 - 22:00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Policies & Social -->
                <div class="col-lg-7">
                    <!-- Chính sách nổi bật -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-3">Chính Sách Nổi Bật</h4>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Miễn phí giao hàng cho đơn từ 5 triệu đồng</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Hỗ trợ đổi trả trong vòng 7 ngày</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Cam kết hàng chính hãng 100%</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Hỗ trợ kỹ thuật và tư vấn 24/7</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Kết nối với chúng tôi -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-3">Kết Nối Với Chúng Tôi</h4>
                            <p class="text-muted">Theo dõi chúng tôi trên các nền tảng mạng xã hội để cập nhật những thông tin và ưu đãi mới nhất.</p>
                            <div class="d-flex gap-3">
                                <a href="#" class="btn btn-primary btn-lg rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-lg rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-youtube"></i>
                                </a>
                                <a href="#" class="btn btn-info text-white btn-lg rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-dark btn-lg rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-tiktok"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-5">
        <div class="container">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.564595138596!2d105.82218951540296!3d21.01009419377701!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab7c15633b23%3A0x42725025737333b8!2zMTc1IFAuIFTDonkgU8ahbiwgVHJ1bmcgTGnhu4d0LCDEkOG7kW5nIMSQYSwgSMOgIE7hu5lpLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1685000000000!5m2!1svi!2s"
                        width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>

@endsection