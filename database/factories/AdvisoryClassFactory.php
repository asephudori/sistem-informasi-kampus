<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Lecturer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdvisoryClass>
 */
class AdvisoryClassFactory extends Factory
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
            'class_year' => $this->faker->year(),
        ];
    }
}
