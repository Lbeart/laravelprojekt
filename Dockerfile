FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy source code
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Clear config
RUN php artisan config:clear

# Copy nginx config
COPY nginx.conf /etc/nginx/nginx.conf

# Create supervisor config
RUN mkdir -p /var/log/supervisor
COPY supervisord.conf /etc/supervisord.conf

# Expose the port used by the application
EXPOSE 8080

# Start supervisor to run both php-fpm and nginx
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]