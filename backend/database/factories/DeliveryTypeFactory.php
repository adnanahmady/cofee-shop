<?php

namespace Database\Factories;

use App\Models\DeliveryType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeliveryType>
 */
class DeliveryTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            DeliveryType::NAME => fake()->name(),
        ];
    }
}
