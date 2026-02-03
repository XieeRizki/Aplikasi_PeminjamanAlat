<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsPetugas
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if (!auth()->check() || (!$user->isPetugas() && !$user->isAdmin())) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak!');
        }

        return $next($request);
    }
}