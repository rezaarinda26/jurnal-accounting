<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->isMethod('GET') && (!auth()->check() || !auth()->user()->isAdmin())) {
            return back()->withErrors(['Maaf, akun demo tidak diizinkan melakukan aksi ini.']);
        }

        return $next($request);
    }
}
