<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin Panenku',
            'email' => 'admin@panenku.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Create Regular User
        User::create([
            'name' => 'Pembeli Demo',
            'email' => 'user@panenku.com',
            'password' => Hash::make('user123'),
            'role' => 'user'
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Sayuran', 'description' => 'Berbagai macam sayuran segar'],
            ['name' => 'Buah-buahan', 'description' => 'Buah segar dari kebun'],
            ['name' => 'Beras & Biji-bijian', 'description' => 'Beras dan biji-bijian berkualitas'],
            ['name' => 'Hasil Olahan', 'description' => 'Produk olahan dari hasil pertanian']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Products
        $products = [
            // Sayuran
            ['name' => 'Kangkung Segar', 'description' => 'Kangkung segar dari petani lokal', 'price' => 5000, 'stock' => 100, 'category_id' => 1],
            ['name' => 'Bayam Hijau', 'description' => 'Bayam hijau organik', 'price' => 7000, 'stock' => 80, 'category_id' => 1],
            ['name' => 'Cabai Merah', 'description' => 'Cabai merah pedas berkualitas', 'price' => 25000, 'stock' => 50, 'category_id' => 1],
            
            // Buah-buahan
            ['name' => 'Pisang Cavendish', 'description' => 'Pisang cavendish manis', 'price' => 15000, 'stock' => 60, 'category_id' => 2],
            ['name' => 'Mangga Harum Manis', 'description' => 'Mangga harum manis premium', 'price' => 35000, 'stock' => 40, 'category_id' => 2],
            ['name' => 'Jeruk Manis', 'description' => 'Jeruk manis segar', 'price' => 20000, 'stock' => 70, 'category_id' => 2],
            
            // Beras & Biji-bijian
            ['name' => 'Beras Premium', 'description' => 'Beras premium kualitas terbaik', 'price' => 85000, 'stock' => 30, 'category_id' => 3],
            ['name' => 'Jagung Manis', 'description' => 'Jagung manis segar', 'price' => 12000, 'stock' => 90, 'category_id' => 3],
            
            // Hasil Olahan
            ['name' => 'Keripik Singkong', 'description' => 'Keripik singkong renyah', 'price' => 18000, 'stock' => 45, 'category_id' => 4],
            ['name' => 'Abon Sapi', 'description' => 'Abon sapi berkualitas tinggi', 'price' => 65000, 'stock' => 25, 'category_id' => 4]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
