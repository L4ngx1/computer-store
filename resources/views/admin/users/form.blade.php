@extends('admin.master')

@section('title', isset($user) ? 'Chỉnh sửa Người dùng' : 'Tạo Người dùng mới')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-semibold">
                <i class="bi bi-{{ isset($user) ? 'pencil' : 'plus-circle' }}"></i> 
                {{ isset($user) ? 'Chỉnh sửa Người dùng' : 'Tạo Người dùng mới' }}
            </h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST">
                        @csrf
                        @if(isset($user))
                            @method('PUT')
                        @endif

                        <!-- Account Information -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-person"></i> Thông tin tài khoản
                            </h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">
                                        Mật khẩu 
                                        @if(isset($user))
                                            <span class="text-muted small">(Để trống nếu không đổi)</span>
                                        @else
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" {{ !isset($user) ? 'required' : '' }}>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">
                                        Xác nhận mật khẩu
                                        @if(!isset($user))
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" {{ !isset($user) ? 'required' : '' }}>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="role" class="form-label">Vai trò <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                        <option value="">-- Chọn vai trò --</option>
                                        <option value="user" {{ old('role', $user->role ?? '') == 'user' ? 'selected' : '' }}>
                                            Người dùng
                                        </option>
                                        <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>
                                            Admin
                                        </option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-telephone"></i> Thông tin liên hệ
                            </h5>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Điện thoại</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-map"></i> Địa chỉ
                            </h5>

                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3">{{ old('address', $user->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> 
                                {{ isset($user) ? 'Cập nhật' : 'Tạo mới' }}
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            @if(isset($user))
                <div class="card shadow-sm mb-4 border-info">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <div class="avatar-circle bg-info text-white rounded-circle mx-auto" 
                                 style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; font-size: 32px;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
                    </div>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Lưu ý</h6>
                </div>
                <div class="card-body small">
                    <ul class="mb-0 ps-3">
                        <li class="mb-2">Email phải là duy nhất và có định dạng hợp lệ</li>
                        <li class="mb-2">Mật khẩu tối thiểu 6 ký tự</li>
                        <li class="mb-2">Admin có quyền truy cập tất cả chức năng quản lý</li>
                        <li>Người dùng bình thường chỉ có thể sử dụng tính năng khách hàng</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
