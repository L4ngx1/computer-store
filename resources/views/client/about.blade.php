@extends('layouts.site')

@section('title', 'Về Chúng Tôi')

@section('content')

    <!-- Banner -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h1 class="fw-bold display-5">Về Chúng Tôi</h1>
            <p class="text-muted mt-3">
                Chuyên cung cấp linh kiện máy tính, laptop và thiết bị công nghệ chính hãng.
            </p>
        </div>
    </section>

    <!-- Section 1 -->
    <section class="py-5 bg-dark text-white">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">
                        Một Doanh Nghiệp Không Ngừng Phát Triển
                    </h2>

                    <p class="lead">
                        Chúng tôi bắt đầu từ một cửa hàng nhỏ chuyên cung cấp linh kiện máy tính.
                        Sau nhiều năm phát triển, chúng tôi đã trở thành địa chỉ đáng tin cậy
                        của hàng nghìn khách hàng trên toàn quốc.
                    </p>

                    <p>
                        Mục tiêu của chúng tôi là mang đến những sản phẩm công nghệ chất lượng cao,
                        giá cả hợp lý cùng dịch vụ chăm sóc khách hàng chuyên nghiệp.
                    </p>
                </div>

                <div class="col-lg-6 text-center">
                    <img src="{{ asset('storage/images/about/about1.jpg') }}" style="width: 300px; height: 300px; object-fit: cover;" class="img-fluid rounded shadow"
                        alt="Giới thiệu">
                </div>

            </div>
        </div>
    </section>

    <!-- Section 2 -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6 text-center">
                    <img src="{{ asset('storage/images/about/about2.jpg') }}" style="width: 300px; height: 300px; object-fit: cover;" class="img-fluid rounded" alt="Shop">
                </div>

                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">
                        Cửa Hàng Công Nghệ Hàng Đầu
                    </h2>

                    <p class="lead">
                        Chúng tôi cung cấp đầy đủ linh kiện PC, laptop, màn hình,
                        thiết bị gaming và phụ kiện công nghệ.
                    </p>

                    <p>
                        Tất cả sản phẩm đều được nhập khẩu chính hãng từ những thương hiệu
                        nổi tiếng như ASUS, MSI, Gigabyte, Dell, HP, Lenovo...
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- Section 3 -->
    <section class="py-5 bg-dark text-white">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">
                        Bạn Luôn Được Đảm Bảo An Toàn
                    </h2>

                    <p class="lead">
                        Sự hài lòng của khách hàng là ưu tiên hàng đầu của chúng tôi.
                    </p>

                    <p>
                        Đội ngũ kỹ thuật viên giàu kinh nghiệm luôn kiểm tra sản phẩm kỹ lưỡng
                        trước khi giao tới khách hàng, đảm bảo chất lượng và độ ổn định cao nhất.
                    </p>
                </div>

                <div class="col-lg-6 text-center">
                    <img src="{{ asset('storage/images/about/about3.png') }}" style="width: 300px; height: 300px; object-fit: cover;" class="img-fluid rounded shadow"
                        alt="An toàn">
                </div>

            </div>
        </div>
    </section>

    <!-- Section 4 -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6 text-center">
                    <img src="{{ asset('storage/images/about/about4.png') }}" style="width: 300px; height: 300px; object-fit: cover;" class="img-fluid rounded" alt="Chất lượng">
                </div>

                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">
                        Chất Lượng Sản Phẩm Cao Nhất
                    </h2>

                    <p class="lead">
                        Chúng tôi cam kết chỉ phân phối sản phẩm chính hãng 100%.
                    </p>

                    <p>
                        Mỗi sản phẩm đều trải qua quy trình kiểm tra nghiêm ngặt,
                        đảm bảo hiệu năng, độ bền và sự ổn định khi sử dụng.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- Section 5 -->
    <section class="py-5 bg-dark text-white">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">
                        Giao Hàng Toàn Quốc
                    </h2>

                    <p class="lead">
                        Dù bạn ở bất cứ đâu, chúng tôi đều có thể giao hàng tận nơi.
                    </p>

                    <p>
                        Hệ thống vận chuyển chuyên nghiệp giúp đơn hàng đến tay khách hàng
                        nhanh chóng, an toàn và đúng thời gian cam kết.
                    </p>
                </div>

                <div class="col-lg-6 text-center">
                    <img src="{{ asset('storage/images/about/about5.svg') }}" style="width: 300px; height: 300px; object-fit: cover;" class="img-fluid rounded shadow"
                        alt="Giao hàng">
                </div>

            </div>
        </div>
    </section>

    <!-- Thống kê -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row text-center">

                <div class="col-md-3">
                    <h2 class="fw-bold">10.000+</h2>
                    <p>Khách Hàng</p>
                </div>

                <div class="col-md-3">
                    <h2 class="fw-bold">5.000+</h2>
                    <p>Sản Phẩm</p>
                </div>

                <div class="col-md-3">
                    <h2 class="fw-bold">100+</h2>
                    <p>Thương Hiệu</p>
                </div>

                <div class="col-md-3">
                    <h2 class="fw-bold">24/7</h2>
                    <p>Hỗ Trợ</p>
                </div>

            </div>
        </div>
    </section>

    <!-- Khách hàng nói gì -->
    <section class="py-5 bg-light">
        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold">Khách Hàng Nói Gì?</h2>
            </div>

            <div class="card border-0 shadow">
                <div class="card-body p-5 text-center">

                    <i class="fas fa-quote-left fa-3x text-primary mb-4"></i>

                    <p class="lead">
                        "Sản phẩm chất lượng, giao hàng nhanh và đội ngũ hỗ trợ rất nhiệt tình.
                        Tôi sẽ tiếp tục mua sắm tại đây."
                    </p>

                    <h6 class="fw-bold mt-4">
                        Nguyễn Văn A
                    </h6>

                </div>
            </div>

        </div>
    </section>

@endsection