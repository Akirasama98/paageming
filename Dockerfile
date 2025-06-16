FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    nodejs \
    npm \
    libcurl4-openssl-dev \
    libssl-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (including ones needed for Firebase)
RUN docker-php-ext-install \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    curl \
    json

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Verify Composer installation
RUN composer --version

# Set working directory
WORKDIR /var/www

# Copy composer files first (for better Docker layer caching)
COPY composer.json composer.lock ./

# Debug: Check if files are copied correctly
RUN ls -la && cat composer.json | head -20

# Install PHP dependencies with verbose output for debugging
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --verbose

# Copy package.json files for Node dependencies
COPY package*.json ./

# Install Node dependencies
RUN npm ci

# Copy the rest of the application
COPY . .

# Build assets
RUN npm run build

# Create necessary directories
RUN mkdir -p storage/logs storage/framework/sessions storage/framework/views storage/framework/cache bootstrap/cache

# Create SQLite database
RUN touch database/database.sqlite

# Set permissions
RUN chmod -R 755 storage bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache database/database.sqlite

# Generate app key
RUN php artisan key:generate --force

# Clear config cache before migrations
RUN php artisan config:clear

# Run migrations and seeders
RUN php artisan migrate --force
RUN php artisan db:seed --force

# Generate API docs
RUN php artisan l5-swagger:generate

# Expose port
EXPOSE $PORT

# Start application
CMD php artisan serve --host=0.0.0.0 --port=$PORT
