# ─────────────────────────────────────────────────────────────────────────────
# STAGE 2: Final image with PHP-FPM on Alpine using apk (jo apt-get)
# ─────────────────────────────────────────────────────────────────────────────
FROM php:8.2-fpm-alpine

# Instalimi i paketave Alpine dhe ekstensioneve të PHP
RUN apk update && apk add --no-cache \
    nginx \
    supervisor \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    unzip \
    git \
    curl \
    oniguruma-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath gd zip xml

WORKDIR /var/www

# Kopjo të gjithë kodin
COPY . .

# Kopjo composer dhe vendor nëse ke build_vendor stage (duhet të kesh një stage me emrin build_vendor)
COPY --from=build_vendor /usr/bin/composer /usr/bin/composer
COPY --from=build_vendor /app/vendor ./vendor

# Konfigurimet & startup
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Lejet
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

EXPOSE 8080
CMD ["/bin/sh", "/start.sh"]