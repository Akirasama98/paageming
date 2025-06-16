<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return response()->json([
        'message' => 'Paageming API - Marketplace Hasil Pertanian',
        'version' => '1.0.0',
        'documentation' => url('/api/documentation'),
        'swagger_info' => url('/swagger-info.html'),
        'endpoints' => [
            'products' => url('/api/products'),
            'categories' => url('/api/categories'),
            'auth' => [
                'register' => url('/api/register'),
                'login' => url('/api/login')
            ]
        ]
    ]);
});

// Route untuk serve api-docs.json secara langsung
Route::get('/docs/api-docs.json', function () {
    $filePath = storage_path('api-docs/api-docs.json');
    
    if (!file_exists($filePath)) {
        return response()->json(['error' => 'API documentation not found'], 404);
    }
    
    return response()->json(json_decode(file_get_contents($filePath), true));
});

// Route debug untuk cek status Swagger
Route::get('/debug/swagger', function () {
    $configPath = config_path('l5-swagger.php');
    $storagePath = storage_path('api-docs');
    $jsonPath = storage_path('api-docs/api-docs.json');
    
    return response()->json([
        'config_exists' => file_exists($configPath),
        'storage_path_exists' => is_dir($storagePath),
        'storage_writable' => is_writable($storagePath),
        'json_file_exists' => file_exists($jsonPath),
        'json_file_size' => file_exists($jsonPath) ? filesize($jsonPath) : 0,
        'storage_contents' => is_dir($storagePath) ? scandir($storagePath) : [],
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
    ]);
});

// Route untuk regenerate Swagger docs
Route::get('/generate-docs', function () {
    try {
        Artisan::call('l5-swagger:generate');
        
        $jsonPath = storage_path('api-docs/api-docs.json');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Swagger docs generated successfully',
            'file_exists' => file_exists($jsonPath),
            'file_size' => file_exists($jsonPath) ? filesize($jsonPath) : 0,
            'output' => Artisan::output()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
