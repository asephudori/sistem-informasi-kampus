<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $permissionSuffix = in_array($request->method(), ['GET', 'HEAD']) ? '--r' : '--rw';
        $currentRoute = request()->route()->uri;
        // $currentRoute = '/api/cek-bansos/';
        if (strpos($currentRoute, '{') === false) {
            $permissionNeeded = rtrim($currentRoute, '/'); // Jika tidak ada, kembalikan string asli dan string kosong
        } else {
            $parts = explode("{", $currentRoute, 2);
            $permissionNeeded = rtrim($parts[0], '/');
        }

        $permissionNeeded .= $permissionSuffix;
        $user = $request->user();
        $hasPermission = $user->admin->permissionRole->permissions->contains('name', $permissionNeeded);

        if ($hasPermission) {
            return $next($request);
        } else {
            return response()->json(['message' => 'Access denied'], 403);
        }
    }
}
