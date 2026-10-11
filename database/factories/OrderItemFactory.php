<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<\App\Models\OrderItem> */
class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $price = (int) (round(fake()->numberBetween(25000, 5000000) / 5000) * 5000);
        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'quantity' => fake()->numberBetween(1, 5),
            'price' => $price,
        ];
    }
}
