<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::query()->with(['user:id,name,email', 'actor:id,name']);

        if ($request->filled('action')) {
            $query->where('action', $request->string('action'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $logs = $query->latest()->paginate(20)->withQueryString();
        $actions = AuditLog::query()->select('action')->distinct()->orderBy('action')->pluck('action');

        return view('admin.audit-logs.index', compact('logs', 'actions'));
    }

    public function undo(Request $request, AuditLog $auditLog): RedirectResponse
    {
        if (! in_array($auditLog->action, ['user.locked', 'user.unlocked', 'user.updated'], true)) {
            return back()->with('error', 'Hoạt động này không thể hoàn tác an toàn.');
        }

        $user = $auditLog->user;
        if (! $user) {
            return back()->with('error', 'Người dùng liên quan không còn tồn tại.');
        }

        DB::transaction(function () use ($auditLog, $user, $request) {
            if ($auditLog->action === 'user.updated') {
                $previousValues = $auditLog->metadata['previous_values'] ?? null;
                abort_unless(is_array($previousValues), 422, 'Nhật ký này không có dữ liệu khôi phục.');
                $user->update($previousValues);
            } else {
                $previousState = $auditLog->metadata['previous_state'] ?? null;
                abort_unless(is_array($previousState), 422, 'Nhật ký này không có trạng thái khôi phục.');
                $user->update([
                    'is_locked' => (bool) ($previousState['is_locked'] ?? false),
                    'locked_at' => $previousState['locked_at'] ?? null,
                    'locked_reason' => $previousState['locked_reason'] ?? null,
                ]);
            }

            AuditLog::create([
                'user_id' => $user->id,
                'actor_id' => Auth::id(),
                'action' => 'audit.undone',
                'description' => "Hoàn tác: {$auditLog->description}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => ['source_audit_log_id' => $auditLog->id],
            ]);
        });

        return back()->with('success', 'Đã hoàn tác hoạt động thành công.');
    }
}
