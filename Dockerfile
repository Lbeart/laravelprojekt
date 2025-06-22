# =============================
# STAGE 1: VETËM PËR COMPOSER
# =============================
FROM composer:latest AS composerstage

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# =============================
# STAGE 2: PHP + NGINX + APP
# =============================
FROM php:8.2-fpm

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

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# MERR COMPOSER nga stage i parë
COPY --from=composerstage /usr/bin/composer /usr/bin/composer

# Vendos app-in
WORKDIR /var/www
COPY . .

# Vendos vendor folder nga composer stage
COPY --from=composerstage /app/vendor ./vendor

# Konfigurimet e nginx dhe supervisor
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Lejet
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

EXPOSE 8080

CMD ["/bin/bash", "/start.sh"]
