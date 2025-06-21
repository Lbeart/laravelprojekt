#!/bin/sh

cd /var/www

# Fix permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Laravel setup
php artisan config:clear
php artisan migrate --force
php artisan config:cache

# Start NGINX dhe PHP-FPM
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
