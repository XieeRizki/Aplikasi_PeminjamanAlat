<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->level;

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        return abort(403, 'Anda tidak memiliki akses ke halaman ini!');
    }
}