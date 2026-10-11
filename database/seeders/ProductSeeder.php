<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /** @var array<string, string[]> product names keyed by child category slug */
    private array $productsByCategory = [
        'phones' => ['Aurora X5 Smartphone', 'Nimbus Pro 12', 'Vertex Fold Phone', 'Zephyr Lite 5G', 'Solstice Max Ultra'],
        'laptops' => ['Meridian Air 14 Laptop', 'TitanBook Pro 16', 'Cloudspire Ultrabook', 'Forge RTX Gaming Laptop', 'Nimbus Chromebook N7', 'Atlas Workstation 15'],
        'audio' => ['EchoPulse Wireless Earbuds', 'SonicWave Over-Ear Headphones', 'BassDrop Portable Speaker', 'ClarityPod Studio Mic', 'Resonance Soundbar 300'],
        'cameras' => ['Lumina Mirrorless Camera 4K', 'VistaCam Action Camera', 'PrimeShot DSLR 200D', 'FlexEye Drone Cam', 'Nocturne Night Vision Cam'],
        'mens-clothing' => ['Classic Oxford Shirt', 'Urban Slim Chinos', 'Merino Wool Henley', 'Heritage Denim Jacket', 'Tailored Charcoal Blazer', 'Performance Polo Shirt'],
        'womens-clothing' => ['Floral Midi Dress', 'Cashmere Blend Cardigan', 'High-Rise Skinny Jeans', 'Silk Wrap Blouse', 'Linen Summer Jumpsuit', 'Structured Trench Coat'],
        'shoes' => ['Velocity Running Sneakers', 'Timber Trail Boots', 'Aria Leather Loafers', 'CloudStep Slides', 'Nova Court Classics', 'Alpine Hiking Shoes'],
        'accessories' => ['Minimalist Leather Wallet', 'Titanium Aviator Sunglasses', 'Heritage Canvas Tote', 'Stainless Steel Watch', 'Woven Leather Belt', 'Merino Scarf'],
        'furniture' => ['Nordic Oak Dining Table', 'Velvet Accent Chair', 'Modular Sectional Sofa', 'Standing Desk Pro', 'Rattan Bookshelf', 'Mid-Century Sideboard'],
        'cookware' => ['Cast Iron Skillet 12"', 'Ceramic Nonstick Pan Set', 'Stainless Steel Stockpot', 'Bamboo Cutting Board', 'Precision Chef Knife', 'Silicone Utensil Set'],
        'decor' => ['Ceramic Table Lamp', 'Abstract Wall Art Print', 'Handwoven Area Rug', 'Scented Soy Candle Trio', 'Floating Shelf Set', 'Macramé Wall Hanging'],
        'fitness' => ['Adjustable Dumbbell Set', 'Resistance Band Kit', 'Yoga Mat Pro', 'Kettlebell 16kg', 'Foam Roller Deluxe', 'Jump Rope Speed Pro'],
        'camping' => ['4-Person Dome Tent', 'Ultralight Sleeping Bag', 'Portable Camp Stove', 'Rechargeable Lantern', 'Insulated Cooler Bag', 'Trekking Pole Pair'],
        'cycling' => ['Carbon Road Bike Helmet', 'LED Bike Light Set', 'Padded Cycling Shorts', 'Floor Pump Deluxe', 'Bike Repair Multi-Tool'],
        'fiction' => ['The Silent Meridian', 'Echoes of Ashford', 'The Cartographer\'s Daughter', 'Midnight in Verano', 'The Last Lighthouse Keeper'],
        'non-fiction' => ['Atomic Habits of Great Leaders', 'The Design of Everyday Systems', 'Sapiens: A Graphic History', 'Deep Work in a Distracted Age', 'The Art of Clear Thinking'],
        'science' => ['Astrophysics for Young Minds', 'The Gene: An Intimate History', 'A Brief History of Time', 'The Selfish Gene Revisited', 'Cosmos: A Personal Journey'],
        'skincare' => ['Vitamin C Serum 30ml', 'Hydrating Night Cream', 'Mineral SPF 50 Sunscreen', 'Gentle Foaming Cleanser', 'Retinol Renewal Complex', 'Hyaluronic Acid Booster'],
        'makeup' => ['Velvet Matte Lipstick', 'Luminous Foundation SPF 20', 'Precision Brow Pencil', 'Volumizing Mascara', 'Warm Neutrals Palette', 'Hydrating Tinted Balm'],
        'supplements' => ['Omega-3 Fish Oil Capsules', 'Vitamin D3 + K2 Drops', 'Plant Protein Powder 1kg', 'Magnesium Glycinate', 'Probiotic Complex 50B', 'Turmeric Curcumin 95%'],
    ];

    /** @var array<string, array{min:int,max:int}> rentang harga IDR realistis per kategori */
    private array $priceRangeByCategory = [
        'phones' => ['min' => 1500000, 'max' => 18000000],
        'laptops' => ['min' => 5000000, 'max' => 35000000],
        'audio' => ['min' => 150000, 'max' => 5000000],
        'cameras' => ['min' => 2000000, 'max' => 25000000],
        'mens-clothing' => ['min' => 75000, 'max' => 1200000],
        'womens-clothing' => ['min' => 75000, 'max' => 1500000],
        'shoes' => ['min' => 200000, 'max' => 2500000],
        'accessories' => ['min' => 50000, 'max' => 1500000],
        'furniture' => ['min' => 500000, 'max' => 12000000],
        'cookware' => ['min' => 100000, 'max' => 2000000],
        'decor' => ['min' => 50000, 'max' => 1000000],
        'fitness' => ['min' => 100000, 'max' => 3000000],
        'camping' => ['min' => 150000, 'max' => 5000000],
        'cycling' => ['min' => 150000, 'max' => 4000000],
        'fiction' => ['min' => 60000, 'max' => 250000],
        'non-fiction' => ['min' => 80000, 'max' => 350000],
        'science' => ['min' => 80000, 'max' => 400000],
        'skincare' => ['min' => 40000, 'max' => 500000],
        'makeup' => ['min' => 35000, 'max' => 450000],
        'supplements' => ['min' => 80000, 'max' => 800000],
    ];

    private function realisticPrice(string $slug): int
    {
        $range = $this->priceRangeByCategory[$slug] ?? ['min' => 50000, 'max' => 1000000];
        // Harga Indonesia lazim kelipatan rapi: barang mahal kelipatan 50rb, murah kelipatan 5rb/1rb
        $step = $range['max'] >= 1000000 ? 50000 : ($range['max'] >= 300000 ? 5000 : 1000);

        return (int) (round(fake()->numberBetween($range['min'], $range['max']) / $step) * $step);
    }

    private function description(string $name, string $categoryName): string
    {
        $templates = [
            '%s original bergaransi resmi. Kualitas %s terjamin, cocok untuk pemakaian harian maupun kado.',
            '%s pilihan terlaris di kategori %s. Stok ready, pengiriman cepat dari gudang Jakarta.',
            'Original %s — bahan berkualitas, detail rapi, dan sudah melewati quality check sebelum dikirim.',
        ];

        return sprintf($templates[array_rand($templates)], $name, strtolower($categoryName));
    }

    public function run(): void
    {
        foreach ($this->productsByCategory as $slug => $names) {
            $category = Category::where('slug', $slug)->firstOrFail();

            foreach ($names as $name) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $name,
                    'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 9999),
                    'description' => $this->description($name, $category->name),
                    'price' => $this->realisticPrice($slug),
                    'stock' => fake()->numberBetween(5, 250),
                    'image' => 'products/' . Str::slug($name) . '.jpg',
                    'is_active' => fake()->boolean(90),
                ]);
            }
        }
    }
}
