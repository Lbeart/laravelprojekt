FROM php:8.2-fpm

# Instalimi i pakove të nevojshme
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

# Instalimi i ekstensioneve të PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl

# Krijimi i folderit dhe socketit për php-fpm
RUN mkdir -p /run/php && touch /run/php/php-fpm.sock

# Vendos direktorinë e punës
WORKDIR /var/www

# Kopjo projektin në imazh
COPY . .

# Kopjo composer-in
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalo dependencat me Composer
RUN composer install --no-dev --optimize-autoloader

# Kopjo konfigurimet e nginx dhe supervisor
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Ekspozo portin 80
EXPOSE 80

# Starto supervisor për të menaxhuar nginx dhe php-fpm
CMD ["sh", "/start.sh"]