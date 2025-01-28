<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Semester;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SemesterFee>
 */
class SemesterFeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'semester_id' => function () {
                return Semester::inRandomOrder()->first()->id;
            },
            'student_id' => function () {
                return Student::inRandomOrder()->first()->user_id;
            },
            'transaction_id' => function () {
                return Transaction::inRandomOrder()->first()->id;
            },
            'payment_status' => $this->faker->randomElement(['unpaid', 'awaiting_verification', 'paid']),
            'payment_proof' => $this->faker->imageUrl(),
        ];
    }
}
