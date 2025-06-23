# ─────────────────────────────────────────────────────────────────────────────
# STAGE 2: Final image with PHP-FPM (Debian base)
# ─────────────────────────────────────────────────────────────────────────────
FROM php:8.2-fpm

# Install Nginx, Supervisor, system libs, and PHP extensions in one go
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
      nginx \
      supervisor \
      libpng-dev \
      libzip-dev \
      libxml2-dev \
      unzip \
      git \
      curl \
 && docker-php-ext-install \
      pdo_mysql \
      mbstring \
      exif \
      pcntl \
      bcmath \
      gd \
      zip \
 && rm -rf /var/lib/apt/lists/*

# Bring over Composer and vendor from build stage
COPY --from=build_vendor /usr/bin/composer /usr/bin/composer
COPY --from=build_vendor /app/vendor ./vendor

WORKDIR /var/www

# Copy the rest of your application
COPY . .

# Copy configs & startup script
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Fix permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

EXPOSE 8080
CMD ["/bin/bash", "/start.sh"]
