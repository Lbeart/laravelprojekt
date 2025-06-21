#!/bin/bash

# Start supervisor to manage nginx and php-fpm
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf