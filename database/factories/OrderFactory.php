<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<\App\Models\Order> */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        $total = (int) (round(fake()->numberBetween(50000, 15000000) / 5000) * 5000);
        return [
            'user_id' => User::factory(),
            'total' => $total,
            'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            'shipping_address' => fake()->address(),
        ];
    }
}
