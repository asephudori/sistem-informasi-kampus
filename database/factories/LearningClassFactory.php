<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Semester;
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
        ];
    }
}
