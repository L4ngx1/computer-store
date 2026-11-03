@extends('admin.master')

@section('title', 'Kiểm duyệt đánh giá')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div>
            <h1>Kiểm duyệt đánh giá</h1>
            <p>Duyệt, từ chối hoặc xóa đánh giá sản phẩm của khách hàng.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card admin-filter-card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label" for="q">Tìm kiếm</label>
                    <input type="text" class="form-control" id="q" name="q" value="{{ request('q') }}" placeholder="Nội dung, tên khách, tên sản phẩm...">
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="status">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="rating">Số sao</label>
                    <select class="form-select" id="rating" name="rating">
                        <option value="">Tất cả</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-search"></i> Lọc</button>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-light">Đặt lại</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Khách hàng</th>
                            <th>Đánh giá</th>
                            <th>Nội dung</th>
                            <th>Trạng thái</th>
                            <th>Ngày gửi</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $review)
                            <tr>
                                <td>
                                    <a href="{{ route('client.product', $review->product->slug ?? '#') }}" target="_blank" class="fw-semibold text-decoration-none">
                                        {{ $review->product->name ?? '—' }}
                                    </a>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $review->user->name ?? '—' }}</div>
                                    <div class="text-muted small">{{ $review->user->email ?? '' }}</div>
                                </td>
                                <td class="text-nowrap">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill text-warning' : 'bi-star text-secondary' }}"></i>
                                    @endfor
                                </td>
                                <td style="max-width: 320px;">
                                    <p class="mb-0 text-truncate" title="{{ $review->comment }}">{{ $review->comment }}</p>
                                </td>
                                <td>
                                    @if ($review->is_approved)
                                        <span class="badge bg-success">Đã duyệt</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                    @endif
                                </td>
                                <td class="text-nowrap small">{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end text-nowrap">
                                    @if (! $review->is_approved)
                                        <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Duyệt đánh giá">
                                                <i class="bi bi-check-lg"></i> Duyệt
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.reviews.reject', $review) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Gỡ khỏi công khai">
                                                <i class="bi bi-eye-slash"></i> Gỡ
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" class="d-inline" onsubmit="return confirm('Xóa đánh giá này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa đánh giá">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Không có đánh giá nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $reviews->links() }}
    </div>
</div>
@endsection
