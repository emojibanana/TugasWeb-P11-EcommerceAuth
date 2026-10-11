<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommerceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $products = Product::all();

        // Cart: each user has 1-4 items (firstOrCreate agar aman dari unique)
        foreach ($users as $user) {
            foreach ($products->random(fake()->numberBetween(1, 4)) as $p) {
                Cart::firstOrCreate(
                    ['user_id' => $user->id, 'product_id' => $p->id],
                    ['quantity' => fake()->numberBetween(1, 3)]
                );
            }
        }

        // Wishlist: each user wishes 1-5 products
        foreach ($users as $user) {
            foreach ($products->random(fake()->numberBetween(1, 5)) as $p) {
                Wishlist::firstOrCreate(
                    ['user_id' => $user->id, 'product_id' => $p->id]
                );
            }
        }

        // Reviews: 30 reviews across products
        foreach ($products->random(min(30, $products->count())) as $product) {
            Review::firstOrCreate(
                ['user_id' => $users->random()->id, 'product_id' => $product->id],
                [
                    'rating' => fake()->numberBetween(1, 5),
                    'comment' => fake()->sentence(12),
                    'is_approved' => fake()->boolean(80),
                ]
            );
        }

        // Orders: 10 orders with realistic items and recomputed total
        DB::transaction(function () use ($users, $products) {
            foreach ($users->random(10) as $user) {
                $order = $user->orders()->create([
                    'total' => 0,
                    'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
                    'shipping_address' => fake()->address(),
                ]);

                $total = 0;
                foreach ($products->random(fake()->numberBetween(1, 4)) as $product) {
                    $qty = fake()->numberBetween(1, 3);
                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'price' => $product->price,
                    ]);
                    $total += $product->price * $qty;
                }

                $order->update(['total' => round($total, 2)]);
            }
        });
    }
}