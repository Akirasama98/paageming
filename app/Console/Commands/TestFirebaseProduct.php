<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class TestFirebaseProduct extends Command
{
    protected $signature = 'firebase:test-product';
    protected $description = 'Test Firebase integration with product creation';

    public function handle()
    {
        try {
            $this->info('Testing Firebase integration with product creation...');
            
            // Get or create admin user
            $admin = User::where('role', 'admin')->first();
            if (!$admin) {
                $this->error('No admin user found. Please run database seeder first.');
                return 1;
            }
            
            // Get or create category
            $category = Category::first();
            if (!$category) {
                $category = Category::create([
                    'name' => 'Test Category',
                    'description' => 'Category for testing Firebase integration'
                ]);
                $this->info('✓ Test category created');
            }
            
            // Create test product
            $productData = [
                'name' => 'Test Product Firebase ' . now()->format('H:i:s'),
                'description' => 'Product for testing Firebase integration',
                'price' => 25000,
                'stock' => 50,
                'category_id' => $category->id,
                'image' => 'https://via.placeholder.com/300x300.png?text=Test+Product'
            ];
            
            $product = Product::create($productData);
            $this->info('✓ Product created in MySQL with ID: ' . $product->id);
            
            // Now test Firebase sync (simulating the ProductController logic)
            $factory = (new \Kreait\Firebase\Factory)
                ->withServiceAccount(config('firebase.credentials.file'))
                ->withDatabaseUri(config('firebase.database_url'));
            $database = $factory->createDatabase();
            
            // Sync to Firebase
            $database->getReference('products/'.$product->id)
                ->set($product->toArray());
            $this->info('✓ Product synced to Firebase');
            
            // Verify data in Firebase
            $snapshot = $database->getReference('products/'.$product->id)->getSnapshot();
            $firebaseData = $snapshot->getValue();
            
            if ($firebaseData && $firebaseData['name'] === $product->name) {
                $this->info('✓ Product verified in Firebase');
                $this->info('Firebase data: ' . json_encode($firebaseData, JSON_PRETTY_PRINT));
            } else {
                $this->error('✗ Product verification failed in Firebase');
                return 1;
            }
            
            $this->info('✅ Firebase product integration test completed successfully!');
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Firebase product test failed: ' . $e->getMessage());
            return 1;
        }
    }
}
