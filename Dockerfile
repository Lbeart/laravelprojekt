# ================================
# STAGE 1: Composer dependencies
# ================================
FROM composer:latest AS build_vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ==========================
# STAGE 2: PHP + Laravel App
# ==========================
FROM php:8.1-fpm

# Install system packages
RUN apt-get update && apt-get install -y --no-install-recommends \
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

# Copy composer binary
COPY --from=build_vendor /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy full Laravel project
COPY . .

# Copy vendor folder from composer stage
COPY --from=build_vendor /app/vendor ./vendor

# Copy configs
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

EXPOSE 8080

CMD ["/bin/bash", "/start.sh"]
