<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password';
    protected $description = 'Reset admin password and show credentials';

    public function handle()
    {
        try {
            $this->info('Checking admin users...');
            
            // Find or create admin user
            $admin = User::where('email', 'admin@panenku.com')->first();
            
            if (!$admin) {
                $this->info('Creating new admin user...');
                $admin = User::create([
                    'name' => 'Admin Panenku',
                    'email' => 'admin@panenku.com',
                    'password' => Hash::make('admin123'),
                    'role' => 'admin'
                ]);
                $this->info('✅ Admin user created successfully');
            } else {
                $this->info('Admin user found. Updating password...');
                $admin->password = Hash::make('admin123');
                $admin->save();
                $this->info('✅ Admin password updated successfully');
            }
            
            $this->info('');
            $this->info('=== ADMIN CREDENTIALS ===');
            $this->info('Email: admin@panenku.com');
            $this->info('Password: admin123');
            $this->info('Role: admin');
            $this->info('=========================');
            $this->info('');
            
            // Test login
            if (Hash::check('admin123', $admin->password)) {
                $this->info('✅ Password verification successful');
            } else {
                $this->error('❌ Password verification failed');
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
