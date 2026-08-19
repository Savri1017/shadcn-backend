<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PenggunaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'peran' => fake()->randomElement(['Staff', 'Staff', 'Staff', 'Staff', 'Admin', 'Manager']),
        ];
    }
}