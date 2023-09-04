<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            User::FIRST_NAME => fake()->firstName(),
            User::LAST_NAME => fake()->lastName(),
            User::EMAIL => fake()->unique()->safeEmail(),
            User::PASSWORD => '$2y$10$92IXUNpkjO0rOQ5byMi.' .
                'Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            // remember token field name is not changeable, therefor
            // assigning a constant for it is useless
            'remember_token' => Str::random(10),
        ];
    }
}
