@extends('admin.master')

@section('title', 'Chi tiết Người dùng')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-semibold">
                <i class="bi bi-person-circle"></i> Chi tiết Người dùng
            </h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Account Information -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Thông tin tài khoản</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Tên người dùng</label>
                            <p class="fs-5"><strong>{{ $user->name }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Email</label>
                            <p class="fs-5"><strong>{{ $user->email }}</strong></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Vai trò</label>
                            <p>
                                <span class="badge {{ $user->isAdmin() ? 'bg-danger' : 'bg-info' }} fs-6">
                                    {{ $user->isAdmin() ? 'Admin' : 'Người dùng' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Trạng thái</label>
                            <p>
                                <span class="badge bg-success">Hoạt động</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-telephone"></i> Thông tin liên hệ</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Điện thoại</label>
                            <p class="fs-5">{{ $user->phone ?? '<em class="text-muted">Chưa cập nhật</em>' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Email xác minh</label>
                            <p>
                                @if($user->email_verified_at)
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Đã xác minh</span>
                                @else
                                    <span class="badge bg-warning"><i class="bi bi-exclamation-circle"></i> Chưa xác minh</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-map"></i> Địa chỉ</h5>
                </div>
                <div class="card-body">
                    <p class="fs-5">{{ $user->address ?? '<em class="text-muted">Chưa cập nhật</em>' }}</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Stats -->
        <div class="col-lg-4">
            <!-- User Stats -->
            <div class="card shadow-sm mb-4 border-primary">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <div class="avatar-circle bg-primary text-white rounded-circle mx-auto" 
                             style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; font-size: 32px;">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    </div>
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <span class="badge {{ $user->isAdmin() ? 'bg-danger' : 'bg-info' }} fs-6">
                        {{ $user->isAdmin() ? 'Admin' : 'Người dùng' }}
                    </span>
                </div>
            </div>

            <!-- Order Stats -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Tổng đơn hàng</h6>
                        <span class="badge bg-info">{{ $user->orders()->count() }}</span>
                    </div>
                    <hr>
                    <p class="mb-0 text-muted small">
                        Đơn hàng đã hoàn thành: <strong>{{ $user->orders()->whereNotNull('completed_at')->count() }}</strong>
                    </p>
                </div>
            </div>

            <!-- Created At -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <label class="form-label text-muted">Ngày tạo</label>
                    <p class="mb-2"><strong>{{ $user->created_at->format('d/m/Y H:i') }}</strong></p>
                    <label class="form-label text-muted">Cập nhật lần cuối</label>
                    <p><strong>{{ $user->updated_at->format('d/m/Y H:i') }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
