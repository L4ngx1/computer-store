<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = User::query()->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->string('role')->toString());
        }

        if ($request->filled('q')) {
            $keyword = $request->string('q')->toString();
            $query->where(function ($builder) use ($keyword) {
                $builder->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        $perPage = (int) $request->integer('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $users = $query->paginate($perPage)->withQueryString();

        return $this->paginated($users, 'Lấy danh sách người dùng thành công.');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'role' => 'required|in:admin,customer',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'role' => $validated['role'],
        ]);

        return $this->success($user, 'Tạo người dùng thành công.', 201);
    }

    public function show(User $user): JsonResponse
    {
        return $this->success($user->loadCount('orders'), 'Lấy chi tiết người dùng thành công.');
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'role' => 'sometimes|required|in:admin,customer',
        ]);

        $user->update([
            'name' => $validated['name'] ?? $user->name,
            'email' => $validated['email'] ?? $user->email,
            'phone' => $validated['phone'] ?? $user->phone,
            'address' => $validated['address'] ?? $user->address,
            'role' => $validated['role'] ?? $user->role,
        ]);

        if ($request->filled('password')) {
            $user->password = $validated['password'];
            $user->save();
        }

        return $this->success($user->fresh(), 'Cập nhật người dùng thành công.');
    }

    public function destroy(User $user): JsonResponse
    {
        if (Auth::id() === $user->id) {
            return $this->error('Bạn không thể tự xoá tài khoản của chính mình.', 422);
        }

        $user->delete();

        return $this->success(null, 'Xóa người dùng thành công.');
    }
}
