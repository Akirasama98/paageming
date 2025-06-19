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
            $database->getReference()->remove();            // Seed Categories
            $categories = [
                'cat1' => [
                    'name' => 'Sayuran', 
                    'description' => 'Sayuran segar dari petani lokal',
                    'created_at' => date('Y-m-d\TH:i:s.u\Z'),
                    'updated_at' => date('Y-m-d\TH:i:s.u\Z')
                ],
                'cat2' => [
                    'name' => 'Buah-buahan', 
                    'description' => 'Buah segar berkualitas tinggi',
                    'created_at' => date('Y-m-d\TH:i:s.u\Z'),
                    'updated_at' => date('Y-m-d\TH:i:s.u\Z')
                ],
                'cat3' => [
                    'name' => 'Biji-bijian', 
                    'description' => 'Biji-bijian dan serealia',
                    'created_at' => date('Y-m-d\TH:i:s.u\Z'),
                    'updated_at' => date('Y-m-d\TH:i:s.u\Z')
                ],
                'cat4' => [
                    'name' => 'Olahan', 
                    'description' => 'Produk olahan pertanian',
                    'created_at' => date('Y-m-d\TH:i:s.u\Z'),
                    'updated_at' => date('Y-m-d\TH:i:s.u\Z')
                ]
            ];

            foreach ($categories as $key => $category) {
                $database->getReference('categories/' . $key)->set($category);
            }            // Seed Products
            $products = [
                'prod1' => [
                    'name' => 'Kangkung Segar', 
                    'description' => 'Kangkung segar dari petani lokal', 
                    'price' => 5000, 
                    'stock' => 100, 
                    'category_id' => 'cat1', 
                    'image_url' => null, 
                    'created_at' => date('Y-m-d\TH:i:s.u\Z'), 
                    'updated_at' => date('Y-m-d\TH:i:s.u\Z')
                ],
                'prod2' => [
                    'name' => 'Bayam Hijau', 
                    'description' => 'Bayam hijau organik', 
                    'price' => 7000, 
                    'stock' => 80, 
                    'category_id' => 'cat1', 
                    'image_url' => null, 
                    'created_at' => date('Y-m-d\TH:i:s.u\Z'), 
                    'updated_at' => date('Y-m-d\TH:i:s.u\Z')
                ],
                'prod3' => [
                    'name' => 'Cabai Merah', 
                    'description' => 'Cabai merah pedas berkualitas', 
                    'price' => 25000, 
                    'stock' => 50, 
                    'category_id' => 'cat1', 
                    'image_url' => null, 
                    'created_at' => date('Y-m-d\TH:i:s.u\Z'), 
                    'updated_at' => date('Y-m-d\TH:i:s.u\Z')
                ],
                'prod4' => [
                    'name' => 'Pisang Cavendish', 
                    'description' => 'Pisang cavendish manis', 
                    'price' => 15000, 
                    'stock' => 60, 
                    'category_id' => 'cat2', 
                    'image_url' => null, 
                    'created_at' => date('Y-m-d\TH:i:s.u\Z'), 
                    'updated_at' => date('Y-m-d\TH:i:s.u\Z')
                ],
                'prod5' => [
                    'name' => 'Mangga Harum Manis', 
                    'description' => 'Mangga harum manis premium', 
                    'price' => 35000, 
                    'stock' => 40, 
                    'category_id' => 'cat2', 
                    'image_url' => null, 
                    'created_at' => date('Y-m-d\TH:i:s.u\Z'), 
                    'updated_at' => date('Y-m-d\TH:i:s.u\Z')
                ],
                'prod6' => [
                    'name' => 'Jeruk Manis', 
                    'description' => 'Jeruk manis segar', 
                    'price' => 20000, 
                    'stock' => 70, 
                    'category_id' => 'cat2', 
                    'image_url' => null, 
                    'created_at' => date('Y-m-d\TH:i:s.u\Z'), 
                    'updated_at' => date('Y-m-d\TH:i:s.u\Z')
                ],
                'prod7' => [
                    'name' => 'Beras Premium', 
                    'description' => 'Beras premium kualitas terbaik', 
                    'price' => 85000, 
                    'stock' => 30, 
                    'category_id' => 'cat3', 
                    'image_url' => null, 
                    'created_at' => date('Y-m-d\TH:i:s.u\Z'), 
                    'updated_at' => date('Y-m-d\TH:i:s.u\Z')
                ],
                'prod8' => [
                    'name' => 'Jagung Manis', 
                    'description' => 'Jagung manis segar', 
                    'price' => 12000, 
                    'stock' => 90, 
                    'category_id' => 'cat3', 
                    'image_url' => null, 
                    'created_at' => date('Y-m-d\TH:i:s.u\Z'), 
                    'updated_at' => date('Y-m-d\TH:i:s.u\Z')
                ],
                'prod9' => [
                    'name' => 'Keripik Singkong', 
                    'description' => 'Keripik singkong renyah', 
                    'price' => 18000, 
                    'stock' => 45, 
                    'category_id' => 'cat4', 
                    'image_url' => null, 
                    'created_at' => date('Y-m-d\TH:i:s.u\Z'), 
                    'updated_at' => date('Y-m-d\TH:i:s.u\Z')
                ],
                'prod10' => [
                    'name' => 'Abon Sapi', 
                    'description' => 'Abon sapi berkualitas tinggi', 
                    'price' => 65000, 
                    'stock' => 25, 
                    'category_id' => 'cat4', 
                    'image_url' => null, 
                    'created_at' => date('Y-m-d\TH:i:s.u\Z'), 
                    'updated_at' => date('Y-m-d\TH:i:s.u\Z')
                ]
            ];

            foreach ($products as $key => $product) {
                $database->getReference('products/' . $key)->set($product);
            }            // Seed Users (for authentication)
            $users = [
                'user1' => [
                    'name' => 'Admin Panenku', 
                    'email' => 'admin@panenku.com', 
                    'role' => 'admin', 
                    'created_at' => date('Y-m-d\TH:i:s.u\Z')
                ],
                'user2' => [
                    'name' => 'Pembeli Demo', 
                    'email' => 'user@panenku.com', 
                    'role' => 'user', 
                    'created_at' => date('Y-m-d\TH:i:s.u\Z')
                ]
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
