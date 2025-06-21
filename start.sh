#!/bin/sh

# Jep leje të duhura për storage dhe bootstrap/cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Ruaj konfigurimin e Laravel
php artisan config:cache

# Run migrimet automatikisht në production
php artisan migrate --force

# Starto serverin e Laravel në portin që Railway pret
php artisan serve --host=0.0.0.0 --port=8000
