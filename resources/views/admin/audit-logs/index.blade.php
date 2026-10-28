@extends('admin.master')

@section('title', 'Nhật ký hoạt động')

@section('content')
<div class="container-fluid">
    <div class="card admin-filter-card">
        <div class="card-body">
            <form class="row g-2 align-items-end" method="GET">
                <div class="col-md-5">
                    <label class="form-label">Tìm kiếm</label>
                    <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Nội dung, người dùng hoặc IP">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Loại hoạt động</label>
                    <select class="form-select" name="action">
                        <option value="">Tất cả hoạt động</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" @selected(request('action') === $action)>{{ $action }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary flex-grow-1" type="submit"><i class="bi bi-search me-1"></i>Lọc</button>
                    <a class="btn btn-light flex-grow-1" href="{{ route('admin.audit-logs.index') }}"><i class="bi bi-arrow-counterclockwise me-1"></i>Đặt lại</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Thời gian</th><th>Hoạt động</th><th>Đối tượng</th><th>Người thực hiện</th><th>IP</th><th></th></tr></thead>
                <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="text-nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td><strong>{{ $log->description }}</strong><div class="small text-muted">{{ $log->action }}</div></td>
                        <td>{{ $log->user?->name ?? 'Đã xóa' }}</td>
                        <td>{{ $log->actor?->name ?? 'Hệ thống' }}</td>
                        <td>{{ $log->ip_address ?? '-' }}</td>
                        <td class="text-end">
                            @if(in_array($log->action, ['user.locked', 'user.unlocked', 'user.updated'], true) && $log->user)
                                <form method="POST" action="{{ route('admin.audit-logs.undo', $log) }}" onsubmit="return confirm('Hoàn tác hoạt động này?')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-warning" type="submit">Hoàn tác</button>
                                </form>
                            @else
                                <span class="text-muted small">Chỉ xem</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có nhật ký hoạt động.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4 d-flex justify-content-center">{{ $logs->links() }}</div>
</div>
@endsection
