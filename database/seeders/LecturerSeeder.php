<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lecturer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
        ->count(7)
        ->create()
        ->each(function ($user) {
            Lecturer::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
