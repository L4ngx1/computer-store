<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends ApiController
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::query()
            ->where('email', $credentials['email'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return $this->error('Tai khoan hoac mat khau khong chinh xac.', 401);
        }

        $token = $this->refreshApiToken($user);

        return $this->success([
            'user' => $user->fresh(),
            'api_token' => $token,
            'token_type' => 'Bearer',
        ], 'Dang nhap thanh cong.');
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return $this->error('Ban chua dang nhap.', 401);
        }

        $user->forceFill([
            'api_token' => null,
        ])->save();

        return $this->success(null, 'Dang xuat thanh cong.');
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'role' => 'customer',
        ]);

        $token = $this->refreshApiToken($user);

        return $this->success([
            'user' => $user->fresh(),
            'api_token' => $token,
            'token_type' => 'Bearer',
        ], 'Dang ky thanh cong.', 201);
    }

    public function me(Request $request): JsonResponse
    {
        if (! $request->user()) {
            return $this->error('Ban chua dang nhap.', 401);
        }

        return $this->success([
            'user' => $request->user(),
        ], 'Lay thong tin nguoi dung hien tai thanh cong.');
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return $this->error('Ban chua dang nhap.', 401);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
        ]);

        $user->update($validated);

        return $this->success([
            'user' => $user->fresh(),
        ], 'Cap nhat ho so thanh cong.');
    }

    public function changePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return $this->error('Ban chua dang nhap.', 401);
        }

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return $this->error('Mat khau hien tai khong dung.');
        }

        $user->update([
            'password' => $validated['password'],
        ]);

        return $this->success(null, 'Doi mat khau thanh cong.');
    }

    private function refreshApiToken(User $user): string
    {
        do {
            $token = Str::random(80);
        } while (User::query()->where('api_token', $token)->exists());

        $user->forceFill([
            'api_token' => $token,
        ])->save();

        return $token;
    }
}
