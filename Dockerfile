FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install Node dependencies and build assets
RUN npm ci && npm run build

# Create SQLite database
RUN touch database/database.sqlite

# Set permissions
RUN chmod -R 755 storage bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Generate app key
RUN php artisan key:generate --force

# Run migrations and seeders
RUN php artisan migrate --force
RUN php artisan db:seed --force

# Generate API docs
RUN php artisan l5-swagger:generate

# Expose port
EXPOSE $PORT

# Start application
CMD php artisan serve --host=0.0.0.0 --port=$PORT
