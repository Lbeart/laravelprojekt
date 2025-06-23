# ─────────────────────────────────────────────────────────────────────────────
# STAGE 2: Final image with PHP-FPM on Alpine (using correct php82-* APKs)
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
    php82-pdo_mysql \
    php82-mbstring \
    php82-bcmath \
    php82-gd \
    php82-zip \
    php82-xml

WORKDIR /var/www

# Copy over everything
COPY . .

# Bring in composer & vendor from build stage
COPY --from=build_vendor /usr/bin/composer /usr/bin/composer
COPY --from=build_vendor /app/vendor ./vendor

# Configs & startup
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

EXPOSE 8080
CMD ["/bin/bash", "/start.sh"]
