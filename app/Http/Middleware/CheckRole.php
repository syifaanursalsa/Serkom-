<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    // $roles menampung argumen setelah titik dua, misal role:admin
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, $roles)) {
            abort(403, 'Kamu tidak punya akses ke halaman ini.');
        }
        return $next($request);
    }
}