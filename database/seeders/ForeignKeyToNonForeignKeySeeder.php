<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\Transaction;
use App\Models\StudyProgram;
use App\Models\AdvisoryClass;
use App\Models\LearningClass;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ForeignKeyToNonForeignKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LearningClass::factory()->count(10)->create();
        AdvisoryClass::factory()->count(5)->create();
        StudyProgram::factory()->count(10)->create();
        News::factory()->count(10)->create();
        Transaction::factory()->count(10)->create();
    }
}
