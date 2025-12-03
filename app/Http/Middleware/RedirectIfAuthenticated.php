<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                // Kalau admin → ke halaman admin ikan
                if (Auth::user()->role === 'admin') {
                    return redirect()->route('admin.ikan.index');
                }

                // Kalau pelanggan → ke beranda
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
