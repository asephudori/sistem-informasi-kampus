<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(NonForeignKeySeeder::class);
        // $this->call(PermissionSeeder::class);  // Untuk initial data, bukan dummy
        $this->call(PermissionRoleSeeder::class);
        $this->call(LecturerSeeder::class);
        $this->call(AdminSeeder::class);  // Untuk initial data, bukan dummy
        $this->call(StudyProgramSeeder::class);
        $this->call(ForeignKeyToNonForeignKeySeeder::class);
        $this->call(StudentSeeder::class);
        $this->call(ForeignKeyToForeignKeySeeder::class);
        $this->call(ForeignKeyOnlySeeder::class);
        $this->call(PermissionGroupSeeder::class);  // Untuk initial data, bukan dummy
    }
}
