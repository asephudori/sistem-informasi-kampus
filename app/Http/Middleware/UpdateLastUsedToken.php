<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastUsedToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Jika user terautentikasi, perbarui `last_used_at`
        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->update([
                'last_used_at' => now(),
            ]);
        }

        return $response;
    }
}
