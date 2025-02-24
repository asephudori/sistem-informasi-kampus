<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\PermissionRole;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Initial permission group untuk super admin
        $permissionRole = PermissionRole::create([
            'name' => 'Super Admin Role',
            'description' => 'Admin with read/write permissions on all APIs'
        ]);

        $superAdmin = Admin::findOrFail(1);
        $superAdmin->update(['permission_role_id' => $permissionRole->id]);
    }
}
