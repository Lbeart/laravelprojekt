# ─────────────────────────────────────────────────────────────────────────────
# STAGE 1: Composer dependencies on PHP 8.2 CLI
# ─────────────────────────────────────────────────────────────────────────────
FROM php:8.2-cli AS build_vendor

RUN apt-get update && apt-get install -y --no-install-recommends \
    zip \
    unzip \
    git \
    curl \
  && docker-php-ext-install zip \
  && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ─────────────────────────────────────────────────────────────────────────────
# STAGE 2: Final PHP-FPM Alpine image
# ─────────────────────────────────────────────────────────────────────────────
FROM php:8.2-fpm-alpine

RUN apk update && apk add --no-cache \
    nginx \
    supervisor \
    oniguruma-dev \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    git \
    curl \
    unzip \
    php8-pdo_mysql \
    php8-mbstring \
    php8-bcmath \
    php8-gd \
    php8-zip \
    php8-xml

WORKDIR /var/www

# Copy app & vendor
COPY . .
COPY --from=build_vendor /app/vendor ./vendor

# Copy configs & entrypoint
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

EXPOSE 8080
CMD ["/bin/bash", "/start.sh"]
