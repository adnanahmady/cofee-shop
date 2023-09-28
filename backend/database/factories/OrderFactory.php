<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\DeliveryType;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Order::USER => User::factory(),
            Order::STATUS => OrderStatus::factory(),
            Order::DELIVERY_TYPE => DeliveryType::factory(),
            Order::ADDRESS => Address::factory(),
        ];
    }
}
