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

// Serve api-docs.json secara langsung (untuk Swagger UI di Railway)
Route::get('/docs/api-docs.json', function () {
    $filePath = storage_path('api-docs/api-docs.json');
    if (!file_exists($filePath)) {
        return response()->json(['error' => 'API documentation not found'], 404);
    }
    return response()->file($filePath, [
        'Content-Type' => 'application/json'
    ]);
});

// Alias for L5 Swagger UI default docs path
Route::get('/api/documentation/docs', function () {
    $filePath = storage_path('api-docs/api-docs.json');
    if (!file_exists($filePath)) {
        return response()->json(['error' => 'API documentation not found'], 404);
    }
    return response()->file($filePath, [
        'Content-Type' => 'application/json'
    ]);
});

// Route debug untuk cek status Swagger
Route::get('/debug/swagger', function () {
    $configPath = config_path('l5-swagger.php');
    $storagePath = storage_path('api-docs');
    $jsonPath = storage_path('api-docs/api-docs.json');
    
    return response()->json([
        'status' => 'debug route working',
        'config_exists' => file_exists($configPath),
        'storage_exists' => is_dir($storagePath),
        'storage_writable' => is_writable($storagePath),
        'json_exists' => file_exists($jsonPath),
        'json_size' => file_exists($jsonPath) ? filesize($jsonPath) : 0,
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'environment' => app()->environment(),
        'app_url' => config('app.url'),
    ]);
});

// Route untuk regenerate Swagger docs
Route::get('/generate-docs', function () {
    try {
        // Clear cache first
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        
        // Generate swagger docs
        Artisan::call('l5-swagger:generate');
        
        $jsonPath = storage_path('api-docs/api-docs.json');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Swagger docs generated successfully',
            'file_exists' => file_exists($jsonPath),
            'file_size' => file_exists($jsonPath) ? filesize($jsonPath) : 0,
            'output' => Artisan::output(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Route untuk publish swagger assets
Route::get('/publish-swagger-assets', function () {
    try {
        Artisan::call('vendor:publish', [
            '--provider' => 'L5Swagger\L5SwaggerServiceProvider',
            '--tag' => 'swagger-ui-assets'
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Swagger assets published successfully',
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
