# =============================================================================
# STAGE 1: Composer dependencies
# =============================================================================
FROM composer:latest AS build_vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# =============================================================================
# STAGE 2: PHP + Laravel + Nginx (Alpine)
# =============================================================================
FROM php:8.2-fpm-alpine

# Instalo paketa minimale me apk
RUN apk update && apk add --no-cache \
    nginx \
    git \
    curl \
    unzip \
    libpng-dev \
    libzip-dev \
    supervisor \
    oniguruma-dev \
    libxml2-dev \
    zip

# Instalo PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Merr composer nga stage 1
COPY --from=build_vendor /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Kopjo aplikacionin plotësisht
COPY . .

# Kopjo folderin vendor nga stage 1
COPY --from=build_vendor /app/vendor ./vendor

# Konfigurimet e nginx dhe supervisor
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Jep lejet e duhura
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

EXPOSE 8080

# Nis entrypoint
CMD ["/bin/bash", "/start.sh"]
