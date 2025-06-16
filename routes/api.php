<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// AUTH
Route::post('/register', 'App\\Http\\Controllers\\Api\\AuthController@register');
Route::post('/login', 'App\\Http\\Controllers\\Api\\AuthController@login');

// AUTHENTICATED ROUTES
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', 'App\\Http\\Controllers\\Api\\AuthController@logout');
    
    // ADMIN ONLY ROUTES
    Route::middleware('App\\Http\\Middleware\\AdminMiddleware')->group(function () {
        Route::get('/users', 'App\\Http\\Controllers\\Api\\UserController@index');
        Route::post('/categories', 'App\\Http\\Controllers\\Api\\CategoryController@store');
        Route::put('/categories/{category}', 'App\\Http\\Controllers\\Api\\CategoryController@update');
        Route::delete('/categories/{category}', 'App\\Http\\Controllers\\Api\\CategoryController@destroy');
        Route::post('/products', 'App\\Http\\Controllers\\Api\\ProductController@store');
        Route::put('/products/{product}', 'App\\Http\\Controllers\\Api\\ProductController@update');
        Route::delete('/products/{product}', 'App\\Http\\Controllers\\Api\\ProductController@destroy');
        Route::post('/upload/image', 'App\\Http\\Controllers\\Api\\UploadController@uploadImage');
        Route::post('/orders/{order}/verify', 'App\\Http\\Controllers\\Api\\OrderController@verify');
    });

    // USER ROUTES (authenticated users)
    Route::get('/cart', 'App\\Http\\Controllers\\Api\\CartController@index');
    Route::post('/cart/add', 'App\\Http\\Controllers\\Api\\CartController@add');
    Route::put('/cart/update/{item}', 'App\\Http\\Controllers\\Api\\CartController@update');
    Route::delete('/cart/remove/{item}', 'App\\Http\\Controllers\\Api\\CartController@remove');
    Route::post('/cart/checkout', 'App\\Http\\Controllers\\Api\\CartController@checkout');
    Route::post('/orders/{order}/confirm', 'App\\Http\\Controllers\\Api\\OrderController@confirm');
});

// PUBLIC ROUTES
Route::get('/categories', 'App\\Http\\Controllers\\Api\\CategoryController@index');
Route::get('/categories/{category}', 'App\\Http\\Controllers\\Api\\CategoryController@show');
Route::get('/products', 'App\\Http\\Controllers\\Api\\ProductController@index');
Route::get('/products/{product}', 'App\\Http\\Controllers\\Api\\ProductController@show');
