<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GradeFormat>
 */
class GradeFormatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'min_grade' => $this->faker->randomFloat(2, 0, 100),
            'max_grade' => $this->faker->randomFloat(2, 0, 100),
            'alphabetical_grade' => $this->faker->word(),
        ];
    }
}
