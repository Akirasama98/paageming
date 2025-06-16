<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class TestLoginEndpoint extends Command
{
    protected $signature = 'test:login';
    protected $description = 'Test login endpoint directly';

    public function handle()
    {
        try {
            $this->info('🧪 Testing login endpoint...');
            
            // Ensure admin user exists
            $admin = User::where('email', 'admin@panenku.com')->first();
            if (!$admin) {
                $this->info('Creating admin user...');
                $admin = User::create([
                    'name' => 'Admin Panenku',
                    'email' => 'admin@panenku.com',
                    'password' => Hash::make('admin123'),
                    'role' => 'admin'
                ]);
            }
            
            $this->info("Admin user ID: {$admin->id}");
            $this->info("Email: {$admin->email}");
            $this->info("Password hash: " . substr($admin->password, 0, 30) . "...");
            
            // Test password verification
            $passwordCheck = Hash::check('admin123', $admin->password);
            $this->info("Password verification: " . ($passwordCheck ? '✅ PASS' : '❌ FAIL'));
            
            if (!$passwordCheck) {
                $this->error('Password verification failed. Updating password...');
                $admin->password = Hash::make('admin123');
                $admin->save();
                $this->info('Password updated. Testing again...');
                $passwordCheck = Hash::check('admin123', $admin->password);
                $this->info("New password verification: " . ($passwordCheck ? '✅ PASS' : '❌ FAIL'));
            }
            
            // Test via HTTP
            $this->info('');
            $this->info('🌐 Testing via HTTP request...');
            
            $response = Http::post('http://127.0.0.1:8000/api/login', [
                'email' => 'admin@panenku.com',
                'password' => 'admin123'
            ]);
            
            $this->info("HTTP Status: {$response->status()}");
            $this->info("Response Headers: " . json_encode($response->headers()));
            $this->info("Response Body: " . $response->body());
            
            if ($response->successful()) {
                $data = $response->json();
                $this->info('✅ Login successful via HTTP');
                $this->info("Token: " . substr($data['access_token'], 0, 30) . "...");
                $this->info("User: " . $data['user']['name']);
            } else {
                $this->error('❌ Login failed via HTTP');
                $this->error("Error: " . $response->body());
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}
