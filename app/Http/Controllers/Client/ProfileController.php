<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        return view('client.account', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|min:6|confirmed',
            ]);
            $validated['password'] = bcrypt($request->password);
        }

        $user->update($validated);

        return back()->with('success', 'Cập nhật thông tin thành công');
    }
}
