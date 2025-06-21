FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Clear config cache
RUN php artisan config:clear

# Copy nginx and supervisor configuration
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf

# Create log directory for supervisor
RUN mkdir -p /var/log/supervisor

# Expose port for Railway/Render
EXPOSE 8080

# Start supervisor to run both php-fpm and nginx
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
