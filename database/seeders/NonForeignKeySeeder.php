<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\GradeFormat;
use App\Models\GradeType;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Semester;
use App\Models\TransactionCategory;
use App\Models\UniversityInformation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NonForeignKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GradeFormat::factory()->count(3)->create();
        TransactionCategory::factory()->count(5)->create();
        GradeType::factory()->count(5)->create();
        Faculty::factory()->count(5)->create();
        Semester::factory()->count(5)->create();
        Course::factory()->count(5)->create();
        Classroom::factory()->count(10)->create();
    }
}
