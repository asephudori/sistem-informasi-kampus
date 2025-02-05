<?php

namespace Database\Factories;

use App\Models\Faculty;
use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FacultyLecturer>
 */
class FacultyLecturerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'faculty_id' => function () {
                return Faculty::inRandomOrder()->first()->id;
            },
            'lecturer_id' => function () {
                return Lecturer::inRandomOrder()->first()->user_id;
            },
            'lecturer_position' => $this->faker->randomElement(['Kaprodi', 'Dosen Wali']),
        ];
    }
}
