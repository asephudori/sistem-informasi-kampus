<?php

namespace Database\Factories;

use App\Models\Permission;
use App\Models\PermissionRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PermissionGroup>
 */
class PermissionGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'permission_id' => function () {
                return Permission::inRandomOrder()->first()->id;
            },
            'permission_role_id' => function () {
                return PermissionRole::inRandomOrder()->first()->id;
            },
        ];
    }
}
