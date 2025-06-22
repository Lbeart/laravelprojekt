# Stage 1: Composer 
FROM composer:latest AS composer_stage

# Stage 2: Laravel + PHP + Nginx
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    supervisor

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer_stage /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy the rest of the application
COPY . .

# Copy configs
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

EXPOSE 8080
CMD ["/bin/bash", "/start.sh"]
