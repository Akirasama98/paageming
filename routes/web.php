<?php

use Illuminate\Support\Facades\Route;

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
