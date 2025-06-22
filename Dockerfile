# ─────────────────────────────────────────────────────────────────────────────
# STAGE 1: Install PHP dependencies (on PHP 8.2 CLI)
# ─────────────────────────────────────────────────────────────────────────────
FROM php:8.2-cli AS build_vendor

# Install zip, unzip, git & curl for Composer
RUN apt-get update && apt-get install -y --no-install-recommends \
    zip \
    unzip \
    git \
    curl \
  && docker-php-ext-install zip \
  && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ─────────────────────────────────────────────────────────────────────────────
# STAGE 2: Build final image with PHP-FPM (Alpine for minimal size)
# ─────────────────────────────────────────────────────────────────────────────
FROM php:8.2-fpm-alpine

# Install runtime & web-server packages
RUN apk update && apk add --no-cache \
    nginx \
    supervisor \
    oniguruma-dev \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    git \
    curl \
    unzip

# Build and enable PHP extensions
# Instaloni PHP extensions pa kompilim
RUN apk update && apk add --no-cache \
    php8-pdo \
    php8-pdo_mysql \
    php8-mbstring \
    php8-exif \
    php8-pcntl \
    php8-bcmath \
    php8-gd \
    php8-zip \
    php8-xml

# Bring over Composer binary
COPY --from=build_vendor /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy all application code
COPY . .

# Copy in vendor folder from build_vendor
COPY --from=build_vendor /app/vendor ./vendor

# Copy config files & startup script
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Fix permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

EXPOSE 8080
CMD ["/bin/bash", "/start.sh"]
