<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('admin.login');
        }

        if (Auth::user()->role === 'admin') {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return back()->with('error', 'Bạn không có quyền truy cập vào trang quản trị.');
    }
}
