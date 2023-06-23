<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            OrderItem::CURRENCY => Currency::factory(),
            OrderItem::ORDER => Order::factory(),
            OrderItem::PRICE => fake()->randomNumber(),
            OrderItem::PRODUCT => Product::factory(),
            OrderItem::AMOUNT => fake()->numberBetween(0, 5000),
        ];
    }
}
