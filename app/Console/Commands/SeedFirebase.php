<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Factory;

class SeedFirebase extends Command
{
    protected $signature = 'firebase:seed';
    protected $description = 'Seed Firebase Realtime Database with initial data';

    public function handle()
    {
        try {
            $factory = (new Factory)
                ->withServiceAccount(config('firebase.credentials.file'))
                ->withDatabaseUri(config('firebase.database_url'));
            $database = $factory->createDatabase();

            // Clear existing data
            $database->getReference()->remove();

            // Seed Categories
            $categories = [
                'cat1' => ['id' => 1, 'name' => 'Sayuran', 'description' => 'Berbagai macam sayuran segar'],
                'cat2' => ['id' => 2, 'name' => 'Buah-buahan', 'description' => 'Buah segar dari kebun'],
                'cat3' => ['id' => 3, 'name' => 'Beras & Biji-bijian', 'description' => 'Beras dan biji-bijian berkualitas'],
                'cat4' => ['id' => 4, 'name' => 'Hasil Olahan', 'description' => 'Produk olahan dari hasil pertanian']
            ];

            foreach ($categories as $key => $category) {
                $database->getReference('categories/' . $key)->set($category);
            }

            // Seed Products
            $products = [
                'prod1' => ['id' => 1, 'name' => 'Kangkung Segar', 'description' => 'Kangkung segar dari petani lokal', 'price' => 5000, 'stock' => 100, 'category_id' => 1, 'image_url' => null, 'created_at' => now()->toISOString(), 'updated_at' => now()->toISOString()],
                'prod2' => ['id' => 2, 'name' => 'Bayam Hijau', 'description' => 'Bayam hijau organik', 'price' => 7000, 'stock' => 80, 'category_id' => 1, 'image_url' => null, 'created_at' => now()->toISOString(), 'updated_at' => now()->toISOString()],
                'prod3' => ['id' => 3, 'name' => 'Cabai Merah', 'description' => 'Cabai merah pedas berkualitas', 'price' => 25000, 'stock' => 50, 'category_id' => 1, 'image_url' => null, 'created_at' => now()->toISOString(), 'updated_at' => now()->toISOString()],
                'prod4' => ['id' => 4, 'name' => 'Pisang Cavendish', 'description' => 'Pisang cavendish manis', 'price' => 15000, 'stock' => 60, 'category_id' => 2, 'image_url' => null, 'created_at' => now()->toISOString(), 'updated_at' => now()->toISOString()],
                'prod5' => ['id' => 5, 'name' => 'Mangga Harum Manis', 'description' => 'Mangga harum manis premium', 'price' => 35000, 'stock' => 40, 'category_id' => 2, 'image_url' => null, 'created_at' => now()->toISOString(), 'updated_at' => now()->toISOString()],
                'prod6' => ['id' => 6, 'name' => 'Jeruk Manis', 'description' => 'Jeruk manis segar', 'price' => 20000, 'stock' => 70, 'category_id' => 2, 'image_url' => null, 'created_at' => now()->toISOString(), 'updated_at' => now()->toISOString()],
                'prod7' => ['id' => 7, 'name' => 'Beras Premium', 'description' => 'Beras premium kualitas terbaik', 'price' => 85000, 'stock' => 30, 'category_id' => 3, 'image_url' => null, 'created_at' => now()->toISOString(), 'updated_at' => now()->toISOString()],
                'prod8' => ['id' => 8, 'name' => 'Jagung Manis', 'description' => 'Jagung manis segar', 'price' => 12000, 'stock' => 90, 'category_id' => 3, 'image_url' => null, 'created_at' => now()->toISOString(), 'updated_at' => now()->toISOString()],
                'prod9' => ['id' => 9, 'name' => 'Keripik Singkong', 'description' => 'Keripik singkong renyah', 'price' => 18000, 'stock' => 45, 'category_id' => 4, 'image_url' => null, 'created_at' => now()->toISOString(), 'updated_at' => now()->toISOString()],
                'prod10' => ['id' => 10, 'name' => 'Abon Sapi', 'description' => 'Abon sapi berkualitas tinggi', 'price' => 65000, 'stock' => 25, 'category_id' => 4, 'image_url' => null, 'created_at' => now()->toISOString(), 'updated_at' => now()->toISOString()]
            ];

            foreach ($products as $key => $product) {
                $database->getReference('products/' . $key)->set($product);
            }

            // Seed Users (for authentication)
            $users = [
                'user1' => ['id' => 1, 'name' => 'Admin Panenku', 'email' => 'admin@panenku.com', 'role' => 'admin', 'created_at' => now()->toISOString()],
                'user2' => ['id' => 2, 'name' => 'Pembeli Demo', 'email' => 'user@panenku.com', 'role' => 'user', 'created_at' => now()->toISOString()]
            ];

            foreach ($users as $key => $user) {
                $database->getReference('users/' . $key)->set($user);
            }

            $this->info('✅ Firebase seeded successfully!');
            $this->info('📦 Added ' . count($categories) . ' categories');
            $this->info('🛒 Added ' . count($products) . ' products');
            $this->info('👥 Added ' . count($users) . ' users');

        } catch (\Exception $e) {
            $this->error('❌ Firebase seeding failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
