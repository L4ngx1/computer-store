<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::query()->latest()->paginate(15);

        return $this->paginated($users, 'Lấy danh sách người dùng thành công.');
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return $this->success($user->loadCount('orders'), 'Lấy chi tiết người dùng thành công.');
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        if (Auth::id() === $user->id) {
            return $this->error('Bạn không thể tự xoá tài khoản của chính mình.', 422);
        }

        $user->delete();

        return $this->success(null, 'Xóa người dùng thành công.');
    }
}