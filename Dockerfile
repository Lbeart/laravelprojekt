FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    supervisor

RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl

WORKDIR /var/www

COPY . .

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

COPY nginx.conf /etc/nginx/nginx.conf
COPY start.sh /start.sh
RUN chmod +x /start.sh

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf


RUN chmod +x /start.sh

EXPOSE 80

CMD ["sh", "/start.sh"]
