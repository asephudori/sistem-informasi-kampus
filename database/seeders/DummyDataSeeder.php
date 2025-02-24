<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(NonForeignKeySeeder::class);
        $this->call(LecturerSeeder::class);
        $this->call(StudyProgramSeeder::class);
        $this->call(ForeignKeyToNonForeignKeySeeder::class);
        $this->call(StudentSeeder::class);
        $this->call(ForeignKeyToForeignKeySeeder::class);
        $this->call(ForeignKeyOnlySeeder::class);
    }
}
