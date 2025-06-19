<?php

require 'vendor/autoload.php';

try {
    $config = [
        'credentials' => [
            'file' => 'storage/app/firebase-credentials.json'
        ],
        'database_url' => 'https://panenku-cd8ea-default-rtdb.firebaseio.com/'
    ];
    
    $factory = (new \Kreait\Firebase\Factory)
        ->withServiceAccount($config['credentials']['file'])
        ->withDatabaseUri($config['database_url']);
    
    $database = $factory->createDatabase();
    
    // Test write
    $database->getReference('test')->set(['message' => 'Hello Firebase', 'timestamp' => date('Y-m-d H:i:s')]);
    echo "✅ Firebase write successful\n";
    
    // Test read
    $data = $database->getReference('test')->getValue();
    echo "✅ Firebase read successful: " . json_encode($data) . "\n";
    
    // Test categories
    $categories = $database->getReference('categories')->getValue();
    echo "✅ Categories in Firebase: " . count($categories ?: []) . " items\n";
    
} catch (\Exception $e) {
    echo "❌ Firebase error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
