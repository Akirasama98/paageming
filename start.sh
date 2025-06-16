#!/bin/bash

echo "🚀 Starting Laravel application..."

# Start PHP built-in server for Render
php artisan serve --host=0.0.0.0 --port=$PORT
