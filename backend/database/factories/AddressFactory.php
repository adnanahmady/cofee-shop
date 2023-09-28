<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Address::USER => User::factory(),
            Address::TITLE => fake()->title(),
            Address::CITY => fake()->city(),
            Address::STREET => fake()->streetName(),
            Address::PLATE_NUMBER => 433,
            Address::POSTAL_CODE => fake()->postcode(),
            Address::DESCRIPTION => fake()->text(),
        ];
    }
}
