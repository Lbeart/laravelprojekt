#!/bin/sh

cd /var/www

chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

php artisan config:clear
php artisan migrate --force
php artisan config:cache

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
