@extends('layouts.site')

@section('title', 'Câu Hỏi Thường Gặp')

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="fw-bold">Câu Hỏi Thường Gặp</h1>
        <p class="text-muted">
            Giải đáp những thắc mắc phổ biến của khách hàng khi mua sắm tại cửa hàng.
        </p>
    </div>

    <div class="row">

        <!-- FAQ -->
        <div class="col-lg-8">

            <div class="accordion shadow-sm" id="faqAccordion">

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq1">
                            Tôi có thể đặt hàng như thế nào?
                        </button>
                    </h2>
                    <div id="faq1"
                         class="accordion-collapse collapse show"
                         data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Chọn sản phẩm yêu thích, thêm vào giỏ hàng,
                            tiến hành thanh toán và điền đầy đủ thông tin giao hàng.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq2">
                            Cửa hàng hỗ trợ những phương thức thanh toán nào?
                        </button>
                    </h2>
                    <div id="faq2"
                         class="accordion-collapse collapse"
                         data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Chúng tôi hỗ trợ thanh toán khi nhận hàng (COD),
                            chuyển khoản ngân hàng và các ví điện tử phổ biến.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq3">
                            Thời gian giao hàng mất bao lâu?
                        </button>
                    </h2>
                    <div id="faq3"
                         class="accordion-collapse collapse"
                         data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Thông thường từ 1–5 ngày làm việc tùy khu vực.
                            Các thành phố lớn thường nhận hàng nhanh hơn.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq4">
                            Chính sách bảo hành như thế nào?
                        </button>
                    </h2>
                    <div id="faq4"
                         class="accordion-collapse collapse"
                         data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Tất cả sản phẩm chính hãng đều được bảo hành
                            theo chính sách của nhà sản xuất từ 12 đến 36 tháng.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq5">
                            Tôi có thể đổi trả sản phẩm không?
                        </button>
                    </h2>
                    <div id="faq5"
                         class="accordion-collapse collapse"
                         data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Khách hàng có thể đổi trả trong vòng 7 ngày
                            nếu sản phẩm bị lỗi từ nhà sản xuất hoặc giao sai đơn hàng.
                        </div>
                    </div>
                </div>

                <!-- FAQ MỚI -->

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq6">
                            Làm sao để theo dõi đơn hàng?
                        </button>
                    </h2>
                    <div id="faq6"
                         class="accordion-collapse collapse"
                         data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Sau khi đặt hàng, bạn sẽ nhận được mã đơn hàng qua email hoặc SMS.
                            Bạn có thể dùng mã này để kiểm tra trạng thái đơn hàng trong mục “Đơn hàng của tôi”.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq7">
                            Tôi có thể hủy đơn hàng không?
                        </button>
                    </h2>
                    <div id="faq7"
                         class="accordion-collapse collapse"
                         data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Bạn chỉ có thể hủy đơn hàng khi đơn chưa được xác nhận hoặc chưa giao cho đơn vị vận chuyển.
                            Vui lòng liên hệ hỗ trợ sớm nhất để được xử lý.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq8">
                            Sản phẩm có giống hình ảnh không?
                        </button>
                    </h2>
                    <div id="faq8"
                         class="accordion-collapse collapse"
                         data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Hình ảnh sản phẩm được chụp thật hoặc mô phỏng sát thực tế.
                            Tuy nhiên có thể chênh lệch nhẹ về màu sắc do ánh sáng và thiết bị hiển thị.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq9">
                            Tôi có cần tạo tài khoản để mua hàng không?
                        </button>
                    </h2>
                    <div id="faq9"
                         class="accordion-collapse collapse"
                         data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Bạn không bắt buộc phải tạo tài khoản để mua hàng.
                            Tuy nhiên, có tài khoản sẽ giúp bạn theo dõi đơn hàng và mua lại nhanh hơn.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq10">
                            Có xuất hóa đơn VAT không?
                        </button>
                    </h2>
                    <div id="faq10"
                         class="accordion-collapse collapse"
                         data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Có. Bạn có thể yêu cầu xuất hóa đơn VAT khi đặt hàng bằng cách điền thông tin công ty ở bước thanh toán.
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Hỗ trợ -->
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-3">
                        <i class="fas fa-headset text-primary me-2"></i>
                        Cần Hỗ Trợ?
                    </h4>

                    <p class="text-muted">
                        Nếu bạn không tìm thấy câu trả lời mong muốn,
                        hãy liên hệ với đội ngũ hỗ trợ của chúng tôi.
                    </p>

                    <hr>

                    <p class="mb-2">
                        <i class="fas fa-phone text-primary me-2"></i>
                        (+84) 0364 939 939
                    </p>

                    <p class="mb-2">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        support@shop.com
                    </p>

                    <p class="mb-4">
                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                        175 Tây Sơn, Đống Đa, Hà Nội
                    </p>

                    <a href="{{ route('client.contact') }}"
                       class="btn btn-primary w-100">
                        Liên Hệ Ngay
                    </a>

                </div>
            </div>

            <!-- Chính sách -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Chính Sách Nổi Bật
                    </h5>

                    <ul class="list-unstyled">
                        <li class="mb-2">
                            ✅ Miễn phí giao hàng từ 5 triệu đồng
                        </li>
                        <li class="mb-2">
                            ✅ Đổi trả trong 7 ngày
                        </li>
                        <li class="mb-2">
                            ✅ Hàng chính hãng 100%
                        </li>
                        <li>
                            ✅ Hỗ trợ kỹ thuật 24/7
                        </li>
                    </ul>

                </div>
            </div>

        </div>

    </div>
</div>
@endsection