<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\StudyProgram;
use App\Models\AdvisoryClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'advisory_class_id' => function () {
                return AdvisoryClass::inRandomOrder()->first()->id;
            },
            'study_program_id' => function () {
                return StudyProgram::inRandomOrder()->first()->id;
            },
            'nim' => $this->faker->unique()->numerify('##########'),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'birthplace' => $this->faker->city(),
            'birthdate' => $this->faker->date(),
            'home_address' => $this->faker->address(),
            'current_address'=> $this->faker->address(),
            'home_city_district' => $this->faker->city(),
            'home_postal_code' => $this->faker->postcode(),
            'gender' => $this->faker->randomElement(['male', 'female']),
        ];
    }
}
