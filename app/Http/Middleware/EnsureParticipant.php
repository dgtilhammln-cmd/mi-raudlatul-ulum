<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureParticipant
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isParticipant()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
