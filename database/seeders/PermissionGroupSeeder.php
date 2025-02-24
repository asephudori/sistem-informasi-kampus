<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRoleId = 1; // Ganti dengan ID Permission Role Super Admin

        // Ambil semua ID dari permissions
        $permissionIds = Permission::pluck('id')->toArray();

        // Buat array untuk mass insert
        $permissionGroups = array_map(function ($permissionId) use ($superAdminRoleId) {
            return [
                'permission_id' => $permissionId,
                'permission_role_id' => $superAdminRoleId,
            ];
        }, $permissionIds);

        // Insert sekali tanpa perulangan
        DB::table('permission_groups')->insert($permissionGroups);
    }
}
