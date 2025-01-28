<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\SemesterFee;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ForeignKeyToForeignKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Grade::factory()->count(10)->create();
        SemesterFee::factory()->count(10)->create();
    }
}
