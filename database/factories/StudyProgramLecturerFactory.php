<?php

namespace Database\Factories;

use App\Models\Lecturer;
use App\Models\StudyProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudyProgramLecturer>
 */
class StudyProgramLecturerFactory extends Factory
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
            'study_program_id' => function () {
                return StudyProgram::inRandomOrder()->first()->id;
            },
            'lecturer_position' => $this->faker->randomElement(['Kaprodi', 'Dosen Wali']),
        ];
    }
}
