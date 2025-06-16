<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CheckAdminUser extends Command
{
    protected $signature = 'admin:check';
    protected $description = 'Check and fix admin user credentials';

    public function handle()
    {
        try {
            $this->info('🔍 Checking admin users in database...');
            
            // Get all users
            $users = User::all();
            $this->info("Found {$users->count()} users total");
            
            foreach ($users as $user) {
                $this->info("ID: {$user->id} | Email: {$user->email} | Name: {$user->name} | Role: {$user->role}");
            }
            
            $this->info('');
            $this->info('🔧 Creating/Updating admin user...');
            
            // Delete existing admin if exists
            User::where('email', 'admin@panenku.com')->delete();
            
            // Create fresh admin user
            $admin = User::create([
                'name' => 'Admin Panenku',
                'email' => 'admin@panenku.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now()
            ]);
            
            $this->info('✅ Fresh admin user created');
            $this->info("ID: {$admin->id}");
            $this->info("Email: {$admin->email}");
            $this->info("Name: {$admin->name}");
            $this->info("Role: {$admin->role}");
            
            // Test password verification
            if (Hash::check('admin123', $admin->password)) {
                $this->info('✅ Password verification successful');
            } else {
                $this->error('❌ Password verification failed');
            }
            
            $this->info('');
            $this->info('🧪 Testing login API...');
            
            // Test login via API
            $loginData = [
                'email' => 'admin@panenku.com',
                'password' => 'admin123'
            ];
            
            $this->info('Login data: ' . json_encode($loginData));
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}
