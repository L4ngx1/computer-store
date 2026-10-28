<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role' => 'required|in:user,admin',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);
        AuditLog::create([
            'user_id' => $user->id,
            'actor_id' => Auth::id(),
            'action' => 'user.created',
            'description' => 'Tạo người dùng mới',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Tạo người dùng thành công');
    }

    public function show(User $user): View
    {
        $user->load(['auditLogs' => fn ($query) => $query->with('actor:id,name')->latest()->limit(10)]);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role' => 'required|in:user,admin',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $previousValues = $user->only(['name', 'email', 'phone', 'address', 'role']);
        $user->update($validated);
        AuditLog::create([
            'user_id' => $user->id,
            'actor_id' => Auth::id(),
            'action' => 'user.updated',
            'description' => 'Cập nhật thông tin người dùng',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => ['previous_values' => $previousValues],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Bạn không thể xóa tài khoản đang đăng nhập.');
        }

        AuditLog::create([
            'user_id' => $user->id,
            'actor_id' => Auth::id(),
            'action' => 'user.deleted',
            'description' => 'Xóa người dùng',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Xóa người dùng thành công');
    }

    public function toggleLock(Request $request, User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Bạn không thể khóa tài khoản đang đăng nhập.');
        }

        $previousLockState = [
            'is_locked' => $user->is_locked,
            'locked_at' => $user->locked_at?->toISOString(),
            'locked_reason' => $user->locked_reason,
        ];

        if ($user->is_locked) {
            $user->update([
                'is_locked' => false,
                'locked_at' => null,
                'locked_reason' => null,
            ]);
            $action = 'user.unlocked';
            $description = 'Mở khóa tài khoản';
        } else {
            $validated = $request->validate([
                'locked_reason' => ['nullable', 'string', 'max:255'],
            ]);
            $user->update([
                'is_locked' => true,
                'locked_at' => now(),
                'locked_reason' => $validated['locked_reason'] ?? null,
            ]);
            $action = 'user.locked';
            $description = 'Khóa tài khoản';
        }

        AuditLog::create([
            'user_id' => $user->id,
            'actor_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => [
                'reason' => $user->locked_reason,
                'previous_state' => $previousLockState,
            ],
        ]);

        return back()->with('success', $user->is_locked ? 'Đã khóa tài khoản.' : 'Đã mở khóa tài khoản.');
    }
}
