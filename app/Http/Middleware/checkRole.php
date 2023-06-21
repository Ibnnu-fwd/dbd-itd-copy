<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class checkRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // check if user has the required role
        if (auth()->user()->is_active) {
            if (in_array($request->user()->role, $roles)) {
                return $next($request);
            }
        } else {
            auth()->logout();
            return redirect('/')->with('error', 'Akun anda tidak aktif. Silahkan hubungi administrator.');
        }

        auth()->logout();
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
