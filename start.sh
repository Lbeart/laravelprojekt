#!/bin/bash

php artisan config:cache
php artisan migrate --force || true

/usr/bin/supervisord -c /etc/supervisord.conf