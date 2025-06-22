# ================================
# STAGE 1: Composer dependencies
# ================================
FROM composer:latest AS build_vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ================================
# STAGE 2: PHP + Laravel + Nginx
# ================================
FROM php:8.2-fpm

# Retry logic për apt-get install
RUN apt-get update && \
    for i in 1 2 3; do \
      apt-get install -y --no-install-recommends \
        nginx \
        git \
        curl \
        unzip \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        libzip-dev \
        supervisor && break || sleep 5; \
    done && \
    rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Copy composer binary from the first stage
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
