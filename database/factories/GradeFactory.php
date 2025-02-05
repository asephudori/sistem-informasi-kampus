<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\GradeType;
use App\Models\LearningClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Grade>
 */
class GradeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => function () {
                return Student::inRandomOrder()->first()->user_id;
            },
            'class_id' => function () {
                return LearningClass::inRandomOrder()->first()->id;
            },
            'grade_type_id' => function () {
                return GradeType::inRandomOrder()->first()->id;
            },
            'grade' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
