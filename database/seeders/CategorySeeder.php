<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Electronics' => ['Phones', 'Laptops', 'Audio', 'Cameras'],
            'Fashion' => ['Men\'s Clothing', 'Women\'s Clothing', 'Shoes', 'Accessories'],
            'Home & Kitchen' => ['Furniture', 'Cookware', 'Decor'],
            'Sports & Outdoors' => ['Fitness', 'Camping', 'Cycling'],
            'Books' => ['Fiction', 'Non-Fiction', 'Science'],
            'Beauty & Health' => ['Skincare', 'Makeup', 'Supplements'],
        ];

        foreach ($categories as $parentName => $children) {
            $parent = Category::create([
                'name' => $parentName,
                'slug' => Str::slug($parentName),
            ]);

            foreach ($children as $childName) {
                $parent->children()->create([
                    'name' => $childName,
                    'slug' => Str::slug($childName),
                ]);
            }
        }
    }
}
