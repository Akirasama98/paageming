#!/bin/bash

echo "🚀 Starting Render Build Process for Laravel..."

# Install PHP via package manager if needed
echo "📦 Installing PHP if not available..."
if ! command -v php &> /dev/null; then
    echo "Installing PHP..."
    apt-get update && apt-get install -y php8.2 php8.2-cli php8.2-xml php8.2-curl php8.2-zip php8.2-sqlite3 php8.2-mbstring
fi

# Install Composer if not available
echo "📦 Installing Composer if not available..."
if ! command -v composer &> /dev/null; then
    echo "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
    chmod +x /usr/local/bin/composer
fi

# Install PHP dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Install Node.js dependencies
echo "📦 Installing NPM dependencies..."
npm ci

# Build assets
echo "🏗️ Building assets..."
npm run build

# Generate application key if not exists
echo "🔑 Generating application key..."
php artisan key:generate --force

# Clear and cache config
echo "⚙️ Optimizing configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create SQLite database if not exists
echo "🗄️ Creating SQLite database..."
touch database/database.sqlite

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Seed database if needed
echo "🌱 Seeding database..."
php artisan db:seed --force

# Generate Swagger documentation
echo "📚 Generating API documentation..."
php artisan l5-swagger:generate

# Set proper permissions
echo "🔐 Setting file permissions..."
chmod -R 755 storage bootstrap/cache

echo "✅ Build process completed successfully!"
