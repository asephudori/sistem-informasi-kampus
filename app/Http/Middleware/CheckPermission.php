<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Mendapatkan daftar permission bawaan untuk role non-admin (student & lecturer)
     */
    private function getPermissionNonAdmin(string $role): array
    {
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
                "api/grade-formats--rw.PUT",
                "api/classrooms--r",
                "api/classrooms--rw.POST.PUT",
            ]);
        }

        return $permissions;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $userRole = $user->role();
        $requestMethod = $request->method();

        $permissionSuffix = in_array($requestMethod, ['GET', 'HEAD']) ? '--r' : '--rw';

        $currentRoute = $request->route()->uri();
        $baseRoute = Str::before($currentRoute, '/{');

        $permissionNeeded = $baseRoute . $permissionSuffix;

        if ($user->admin) {
            $hasPermission = $user->admin->permissionRole->permissions->contains('name', $permissionNeeded);
        }
        else {
            $allowedPermissions = $this->getPermissionNonAdmin($userRole);
            $hasPermission = false;

            foreach ($allowedPermissions as $allowedPermission) {
                if ($allowedPermission === $permissionNeeded) {
                    $hasPermission = true;
                    break;
                }

                // Cek jika permission memiliki metode spesifik (misalnya "api/classrooms--rw.POST.PUT")
                if (Str::startsWith($allowedPermission, $baseRoute . '--rw')) {
                    $methodList = explode('.', Str::after($allowedPermission, '--rw'));
                    if (in_array($requestMethod, $methodList)) {
                        $hasPermission = true;
                        break;
                    }
                }
            }
        }

        if ($hasPermission) {
            return $next($request);
        }

        return response()->json(['message' => 'Access denied'], 403);
    }
}
