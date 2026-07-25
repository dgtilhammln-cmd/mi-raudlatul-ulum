<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cacheKey = 'user-last-seen-' . $user->id;

            // Throttle to once per minute to avoid excessive DB writes
            if (!Cache::has($cacheKey)) {
                $user->update(['last_seen_at' => now()]);
                Cache::put($cacheKey, true, 60);
            }
        }

        return $next($request);
    }
}
