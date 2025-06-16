<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Factory;

class TestFirebase extends Command
{
    protected $signature = 'firebase:test';
    protected $description = 'Test Firebase connection';    public function handle()
    {
        try {
            $this->info('Testing Firebase connection...');
            
            // Test credentials file path
            $credentialsPath = config('firebase.credentials.file');
            $databaseUrl = config('firebase.database_url');
            $this->info("Credentials path: $credentialsPath");
            $this->info("Database URL: $databaseUrl");
            
            if (!file_exists($credentialsPath)) {
                $this->error("Credentials file not found: $credentialsPath");
                return 1;
            }
            
            // Try to create Firebase factory with database URL
            $factory = (new Factory)
                ->withServiceAccount($credentialsPath)
                ->withDatabaseUri($databaseUrl);
            $this->info('✓ Firebase factory created successfully');
            
            // Test database connection
            $database = $factory->createDatabase();
            $this->info('✓ Database connection created successfully');
            
            // Test write to database
            $testData = [
                'test' => true,
                'timestamp' => now()->toDateTimeString(),
                'app' => 'Paageming Laravel API'
            ];
            
            $database->getReference('test/connection')->set($testData);
            $this->info('✓ Test data written to Firebase successfully');
            
            // Test read from database
            $snapshot = $database->getReference('test/connection')->getSnapshot();
            $data = $snapshot->getValue();
            
            if ($data && isset($data['test']) && $data['test'] === true) {
                $this->info('✓ Test data read from Firebase successfully');
                $this->info('Data: ' . json_encode($data, JSON_PRETTY_PRINT));
            } else {
                $this->error('✗ Failed to read test data from Firebase');
                return 1;
            }
            
            $this->info('✅ Firebase connection test completed successfully!');
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Firebase connection failed: ' . $e->getMessage());
            return 1;
        }
    }
}
