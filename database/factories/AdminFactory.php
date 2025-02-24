<?php

namespace Database\Factories;

use App\Models\PermissionRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
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
            'permission_role_id' => function () {
                return PermissionRole::inRandomOrder()->first()->id;
            },
            'name' => $this->faker->name(),
            'role' => $this->faker->randomElement(['admin']),
        ];
    }
}
