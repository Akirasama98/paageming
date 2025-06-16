#!/bin/bash

echo "🚀 Building Paageming API for Railway..."

# Install PHP via package manager if needed
echo "📦 Installing PHP if not available..."
if ! command -v php &> /dev/null; then
    echo "Installing PHP..."
    apt-get update && apt-get install -y php8.2 php8.2-cli php8.2-xml php8.2-curl php8.2-zip php8.2-sqlite3 php8.2-mbstring php8.2-gd php8.2-bcmath
fi

# Install Composer if not available
echo "📦 Installing Composer if not available..."
if ! command -v composer &> /dev/null; then
    echo "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
    chmod +x /usr/local/bin/composer
fi

# Set Composer memory limit
export COMPOSER_MEMORY_LIMIT=-1

# Install PHP dependencies
echo "📦 Installing API dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --verbose

# Install Node.js dependencies for asset building
echo "📦 Installing NPM dependencies..."
npm ci

# Build assets (minimal for API)
echo "🏗️ Building assets..."
npm run build

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate --force

# Optimize Laravel for production
echo "⚙️ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create SQLite database
echo "🗄️ Creating SQLite database..."
touch database/database.sqlite

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Seed database with admin user
echo "🌱 Seeding database..."
php artisan db:seed --force

# Generate API documentation
echo "📚 Generating API documentation..."
php artisan l5-swagger:generate

# Set proper permissions
echo "🔐 Setting file permissions..."
chmod -R 755 storage bootstrap/cache

echo "✅ Paageming API build completed successfully!"
