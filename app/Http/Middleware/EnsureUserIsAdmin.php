<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     * Allows read-only (GET) for all authenticated users.
     * Blocks write operations (POST/PATCH/DELETE) for non-admin users.
     * Finance users are redirected to payables instead of getting an error.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Finance role: can only access payable routes and their own profile
        if ($user && $user->isFinance()) {
            // Allow payable routes, profile routes, and pics.quick-store
            if ($request->routeIs('payables.*') || $request->routeIs('profile.*') || $request->routeIs('pics.quick-store')) {
                return $next($request);
            }
            // Redirect all other routes to payables
            return redirect()->route('payables.index')
                ->withErrors(['Akun Anda hanya memiliki akses ke halaman Daftar Hutang.']);
        }

        // Block write operations for non-admin (viewer role)
        if (!$request->isMethod('GET') && (!$user || !$user->isAdmin())) {
            return back()->withErrors(['Maaf, akun demo tidak diizinkan melakukan aksi ini.']);
        }

        return $next($request);
    }
}
