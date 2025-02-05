<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\LearningClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassMember>
 */
class ClassMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'class_id' => function () {
                return LearningClass::inRandomOrder()->first()->id;
            },
            'student_id' => function () {
                return Student::inRandomOrder()->first()->user_id;
            },
            'semester_grades' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
