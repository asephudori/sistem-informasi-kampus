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
        PermissionRole::factory()->count(3)->create();
        UniversityInformation::create([
            'name' => 'Techno Net',
            'address' => 'Jl. Ciledug Raya No. 99, Petukangan Utara, Pesanggrahan, Jakarta Selatan',
            'regency' => 'Jakarta Selatan',
            'postal_code' => '12260',
            'logo' => '',
        ]);
        GradeFormat::factory()->count(3)->create();
        Permission::factory()->count(5)->create();
        TransactionCategory::factory()->count(5)->create();
        GradeType::factory()->count(5)->create();
        Faculty::factory()->count(5)->create();
        Semester::factory()->count(5)->create();
        Course::factory()->count(5)->create();
        Classroom::factory()->count(10)->create();
    }
}
