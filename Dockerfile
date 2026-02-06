FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    libzip-dev \
    unzip \
    zip \
    curl \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Laravel permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Copy Nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Expose Railway port
EXPOSE 8080

# Start services
CMD service nginx start && php-fpm