<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1 Admin User
        User::updateOrCreate(
            ['email' => 'admin@woolywhims.com'],
            [
                'name' => 'Nabila Taskin Simi',
                'password' => Hash::make('password'),
            ]
        );

        // 4 Sample Categories
        $catData = [
            ['name' => 'Cozy Cardigans', 'slug' => 'cozy-cardigans', 'image' => 'categories/cardigan.jpg'],
            ['name' => 'Soft Scarves', 'slug' => 'soft-scarves', 'image' => 'categories/scarves.jpg'],
            ['name' => 'Woolen Wraps', 'slug' => 'woolen-wraps', 'image' => 'categories/wraps.jpg'],
            ['name' => 'Artisan Accessories', 'slug' => 'accessories', 'image' => 'categories/accessories.jpg'],
        ];

        $categories = [];
        foreach ($catData as $data) {
            $categories[$data['name']] = Category::updateOrCreate(
                ['slug' => $data['slug']],
                $data + ['is_active' => true]
            )->id;
        }

        // 8 Sample Products
        $products = [
            ['name' => 'Midnight Mist Cardigan', 'cat' => 'Cozy Cardigans', 'price' => 120, 'stock' => 15],
            ['name' => 'Ivory Cloud Wrap', 'cat' => 'Woolen Wraps', 'price' => 85, 'stock' => 20],
            ['name' => 'Sage Serenity Scarf', 'cat' => 'Soft Scarves', 'price' => 45, 'stock' => 50],
            ['name' => 'Rustic Harvest Beanie', 'cat' => 'Artisan Accessories', 'price' => 30, 'stock' => 100],
            ['name' => 'Ethereal Blue Cardigan', 'cat' => 'Cozy Cardigans', 'price' => 135, 'stock' => 10],
            ['name' => 'Golden Hour Shawl', 'cat' => 'Woolen Wraps', 'price' => 95, 'stock' => 25],
            ['name' => 'Ocean Breeze Scarf', 'cat' => 'Soft Scarves', 'price' => 40, 'stock' => 40],
            ['name' => 'Desert Rose Gloves', 'cat' => 'Artisan Accessories', 'price' => 35, 'stock' => 60],
        ];

        foreach ($products as $prod) {
            Product::updateOrCreate(
                ['slug' => Str::slug($prod['name'])],
                [
                    'name' => $prod['name'],
                    'category_id' => $categories[$prod['cat']],
                    'price' => $prod['price'],
                    'stock' => $prod['stock'],
                    'description' => 'A beautifully handcrafted piece made from the finest ethical wool. Designed for comfort and timeless eloquence.',
                    'thumbnail' => 'products/sample.jpg',
                    'is_active' => true,
                    'is_featured' => true
                ]
            );
        }

        // 2 Sample Coupons
         Coupon::updateOrCreate(['code' => 'WELCOME10'], [
            'type' => 'percentage',
            'value' => 10,
            'max_uses' => 100,
            'is_active' => true,
            'expires_at' => now()->addMonths(1),
        ]);

        Coupon::updateOrCreate(['code' => 'SOFTOPENING'], [
            'type' => 'flat',
            'value' => 50,
            'min_order' => 200,
            'max_uses' => 50,
            'is_active' => true,
            'expires_at' => now()->addMonths(2),
        ]);
    }
}
