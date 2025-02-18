<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Get permission list for non-admin (student & lecturer)
     */
    private function getPermissionNonAdmin(string $role) {
        $permissions = [
            "api/lecturers--r",
            "api/lecturers--rw",
            "api/students--r",
            "api/students--rw",
            "api/courses--r",
            "api/courses--rw",
            "api/learning-classes--r",
            "api/learning-classes--rw",
            "api/advisory-classes--r",
            "api/advisory-classes--rw",
            "api/grades--r",
            "api/grades--rw",
        ];

        if ($role === 'lecturer') {
            $permissions = array_merge($permissions, [
                "api/grade-types--r",
                "api/grade-formats--r",
                "api/grade-formats--rw",
                "api/classrooms--r",
                "api/classrooms--rw",
            ]);
        }

        return $permissions;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $userRole = $user->role();

        $permissionSuffix = in_array($request->method(), ['GET', 'HEAD']) ? '--r' : '--rw';
        $currentRoute = request()->route()->uri;

        if (strpos($currentRoute, '{') === false) {
            $permissionNeeded = rtrim($currentRoute, '/'); // Jika tidak ada, kembalikan string asli dan string kosong
        } else {
            $parts = explode("{", $currentRoute, 2);
            $permissionNeeded = rtrim($parts[0], '/');
        }

        $permissionNeeded .= $permissionSuffix;

        // Jika user adalah admin, ambil permission dari database
        if ($user->admin) {
            $hasPermission = $user->admin->permissionRole->permissions->contains('name', $permissionNeeded);
        }
        // Jika user adalah student atau lecturer, gunakan permission default
        else {
            $allowedPermissions = $this->getPermissionNonAdmin($userRole);
            $hasPermission = in_array($permissionNeeded, $allowedPermissions);
        }

        if ($hasPermission) {
            return $next($request);
        } else {
            return response()->json(['message' => 'Access denied'], 403);
        }
    }
}
