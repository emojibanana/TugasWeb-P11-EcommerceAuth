<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<\App\Models\Product> */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        // Harga IDR realistis kelipatan 5rb (bukanзависимо desimal ala USD)
        $price = (int) (round(fake()->numberBetween(25000, 5000000) / 5000) * 5000);
        return [
            'category_id' => Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(2),
            'price' => $price,
            'stock' => fake()->numberBetween(0, 200),
            'image' => 'products/' . Str::slug($name) . '.jpg',
        ];
    }
}
