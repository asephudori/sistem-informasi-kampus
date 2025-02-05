<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\TransactionCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_category_id' => function () {
                return TransactionCategory::inRandomOrder()->first()->id;
            },
            'admin_id' => function () {
                return Admin::inRandomOrder()->first()->user_id;
            },
            'type' => $this->faker->randomElement(['income', 'expense']),
            'amount' => $this->faker->randomFloat(),
            'description' => $this->faker->paragraph(),
            'proof' => $this->faker->imageUrl(),
            'date' => $this->faker->date(),
            'verification_status' => $this->faker->randomElement(['pending', 'awaiting_verification', 'completed']),
        ];
    }
}
