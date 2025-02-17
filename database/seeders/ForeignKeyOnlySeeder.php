<?php

namespace Database\Seeders;

use App\Models\ClassMember;
use App\Models\FacultyLecturer;
use App\Models\PermissionGroup;
use App\Models\Schedule;
use App\Models\StudyProgramLecturer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ForeignKeyOnlySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PermissionGroup::factory()->count(5)->create();
        FacultyLecturer::factory()->count(5)->create();
        StudyProgramLecturer::factory()->count(5)->create();
        ClassMember::factory()->count(10)->create();
        Schedule::factory()->count(10)->create();
    }
}
