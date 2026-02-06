FROM php:8.2-fpm

# Install system deps
RUN apt-get update && apt-get install -y \
    nginx \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working dir
WORKDIR /var/www

# Copy app
COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader

# Permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Nginx config
COPY docker/nginx.conf /etc/nginx/sites-available/default

EXPOSE 8080

CMD service php-fpm start && nginx -g 'daemon off;'