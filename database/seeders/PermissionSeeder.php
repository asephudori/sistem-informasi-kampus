<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = collect(Route::getRoutes())->map(fn ($route) => $route->uri());

        // Filter hanya route API tanpa parameter (tanpa tanda `{}` di dalamnya)
        $filteredRoutes = $routes->filter(fn ($route) =>
            str_starts_with($route, 'api/') &&
            !str_contains($route, '{') && // Hindari route dengan parameter
            !in_array($route, [
                'api/login',
                'api/logout',
                'api/user'
            ]) // Kecualikan login/logout dan user
        );

        foreach ($filteredRoutes as $route) {
            Permission::updateOrInsert(
                ['name' => $route . '--r'],
                ['description' => "Granted to access GET routes from /$route route"]
            );

            Permission::updateOrInsert(
                ['name' => $route . '--rw'],
                ['description' => "Granted to access GET, POST, PUT, and DELETE routes from /$route route"]
            );
        }
    }
}
