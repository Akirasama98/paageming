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
