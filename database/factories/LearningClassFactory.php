<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Semester;
use App\Models\Classroom;
use App\Models\StudyProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LearningClass>
 */
class LearningClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lecturer_id' => function () {
                return Lecturer::inRandomOrder()->first()->user_id;
            },
            'course_id' => function () {
                return Course::inRandomOrder()->first()->id;
            },
            'semester_id' => function () {
                return Semester::inRandomOrder()->first()->id;
            },
            'study_program_id' => function () {
                return StudyProgram::inRandomOrder()->first()->id;
            },
            'classroom_id' => function () {
                return Classroom::inRandomOrder()->first()->id;
            },
        ];
    }
}
