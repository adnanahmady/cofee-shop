<?php

namespace Database\Factories;

use App\Models\Customization;
use App\Models\Option;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Option>
 */
class OptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Option::NAME => fake()->name(),
            Option::CUSTOMIZATION => Customization::factory(),
        ];
    }
}
