@extends('layouts.site')

@section('title', 'Tài khoản')

@section('content')
    <div class="small text-secondary mb-4">Trang chủ › Tài khoản</div>
    
    <div class="container py-4">
        <h1 class="h2 fw-bold mb-4">
            <i class="bi bi-person-circle"></i> Tài khoản của tôi
        </h1>

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3 mb-4">
                <div class="list-group sticky-top" style="top: 20px;">
                    <a href="#" class="list-group-item list-group-item-action active" data-section="account">
                        <i class="bi bi-person me-2"></i> Thông tin tài khoản
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" data-section="contact">
                        <i class="bi bi-telephone me-2"></i> Thông tin liên hệ
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" data-section="address">
                        <i class="bi bi-map me-2"></i> Địa chỉ
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" data-section="password">
                        <i class="bi bi-shield-lock me-2"></i> Đổi mật khẩu
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" data-section="orders">
                        <i class="bi bi-box me-2"></i> Lịch sử đơn hàng
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action list-group-item-danger w-100 text-start" style="border-top: 1px solid #dee2e6;">
                            <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <form action="{{ route('client.account.update') }}" method="POST" id="profileForm">
                    @csrf
                    @method('PUT')

                    <!-- Account Information -->
                    <div class="card shadow-sm mb-4" data-section-id="account">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="bi bi-person"></i> Thông tin tài khoản
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle"></i> 
                                @if($user->email_verified_at)
                                    Email của bạn đã được xác minh
                                @else
                                    Email của bạn chưa được xác minh. <a href="#">Xác minh ngay</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="card shadow-sm mb-4" data-section-id="contact">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="bi bi-telephone"></i> Thông tin liên hệ
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Điện thoại</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="card shadow-sm mb-4" data-section-id="address">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="bi bi-map"></i> Địa chỉ
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="card shadow-sm mb-4" data-section-id="password">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="bi bi-shield-lock"></i> Đổi mật khẩu
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">Để trống nếu không muốn đổi mật khẩu</p>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="card shadow-sm" data-section-id="account contact address password">
                        <div class="card-body d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Lưu thay đổi
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm" data-section-id="orders">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bi bi-box"></i> Lịch sử đơn hàng
                        </h5>
                    </div>
                    @php
                        $statusLabels = [
                            'pending' => ['Chờ xử lý', 'warning'],
                            'processing' => ['Đang xử lý', 'info'],
                            'shipping' => ['Đang giao', 'primary'],
                            'completed' => ['Hoàn thành', 'success'],
                            'cancelled' => ['Đã hủy', 'danger'],
                        ];
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($user->orders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->id }}</strong></td>
                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td>{{ number_format($order->total_price, 0, ',', '.') }} ₫</td>
                                        <td>
                                            <span class="badge bg-{{ $statusLabels[$order->status][1] ?? 'secondary' }}">
                                                {{ $statusLabels[$order->status][0] ?? $order->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                                                <i class="bi bi-eye"></i> Chi tiết
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox"></i> Bạn chưa có đơn hàng nào
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if ($user && $user->orders->isNotEmpty())
            @foreach ($user->orders as $order)
                <!-- Modal Chi tiết đơn hàng #{{ $order->id }} -->
                <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-light">
                                <h5 class="modal-title fw-bold" id="orderModalLabel{{ $order->id }}">
                                    <i class="bi bi-receipt"></i> Chi tiết đơn hàng #{{ $order->id }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <h6 class="fw-bold text-primary mb-2"><i class="bi bi-person"></i> Thông tin giao nhận</h6>
                                        <div class="p-3 bg-light rounded-3 small">
                                            <p class="mb-1"><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
                                            <p class="mb-1"><strong>Điện thoại:</strong> {{ $order->customer_phone }}</p>
                                            <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
                                            <p class="mb-0"><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold text-primary mb-2"><i class="bi bi-info-circle"></i> Thông tin đơn hàng</h6>
                                        <div class="p-3 bg-light rounded-3 small">
                                            <p class="mb-1"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                            <p class="mb-1"><strong>Trạng thái:</strong> 
                                                <span class="badge bg-{{ $statusLabels[$order->status][1] ?? 'secondary' }}">
                                                    {{ $statusLabels[$order->status][0] ?? $order->status }}
                                                </span>
                                            </p>
                                            <p class="mb-1"><strong>Thanh toán:</strong> {{ $order->payment_method }}</p>
                                            @if($order->note)
                                                <p class="mb-0 text-muted"><strong>Ghi chú:</strong> {{ $order->note }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <h6 class="fw-bold text-primary mb-2"><i class="bi bi-box-seam"></i> Danh sách sản phẩm</h6>
                                <div class="table-responsive border rounded-3">
                                    <table class="table table-hover mb-0 align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Sản phẩm</th>
                                                <th class="text-center" style="width: 80px;">SL</th>
                                                <th class="text-end" style="width: 140px;">Đơn giá</th>
                                                <th class="text-end" style="width: 160px;">Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->items as $item)
                                                <tr>
                                                    <td>
                                                        <div class="fw-semibold">{{ $item->product_name }}</div>
                                                    </td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td class="text-end">{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                                                    <td class="text-end fw-semibold">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} ₫</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr class="fw-bold">
                                                <td colspan="3" class="text-end text-uppercase small">Tổng tiền thanh toán:</td>
                                                <td class="text-end text-danger fs-5">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer bg-light-subtle">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.list-group [data-section]');
            const sections = document.querySelectorAll('[data-section-id]');
            
            function showSection(sectionName) {
                // Hide all sections
                sections.forEach(section => {
                    const sectionIds = section.getAttribute('data-section-id');
                    // Hide section unless it's multi-section (for Save Button)
                    if (sectionIds && sectionIds.includes(' ')) {
                        // Multi-section element (like Save Button) - only hide if current section is orders
                        if (sectionName === 'orders') {
                            section.style.display = 'none';
                        } else {
                            section.style.display = 'block';
                        }
                    } else {
                        // Single section element
                        section.style.display = 'none';
                    }
                });
                
                // Show the selected section
                const selectedSection = document.querySelector(`[data-section-id="${sectionName}"]`);
                if (selectedSection) {
                    selectedSection.style.display = 'block';
                    
                    // Scroll to section smoothly
                    setTimeout(() => {
                        selectedSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                }
                
                // Update active menu item
                menuItems.forEach(item => {
                    item.classList.remove('active');
                });
                const activeItem = document.querySelector(`[data-section="${sectionName}"]`);
                if (activeItem) {
                    activeItem.classList.add('active');
                }
            }
            
            // Add click handlers to menu items
            menuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const sectionName = this.getAttribute('data-section');
                    showSection(sectionName);
                });
            });
            
            // Initialize - show only account section by default
            showSection('account');
        });
    </script>
@endsection
