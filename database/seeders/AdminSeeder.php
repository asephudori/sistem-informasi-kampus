<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Initial data super admin
        $user = User::create([
            'username' => 'super.admin.default',
            'password' => bcrypt('super.admin.default.pw'),
        ]);
        Admin::create([
            'user_id' => $user->id,
            'name' => 'Super Admin Default',
            'role' => 'super admin'
        ]);

        // User::factory()
        // ->count(3)
        // ->create()
        // ->each(function ($user) {
        //     Admin::factory()->create([
        //         'user_id' => $user->id,
        //     ]);
        // });
    }
}
