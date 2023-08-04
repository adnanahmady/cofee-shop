<?php

namespace Database\Factories;

use App\Models\Customization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customization>
 */
class CustomizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Customization::NAME => fake()->name(),
        ];
    }
}
