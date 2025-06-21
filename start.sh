#!/bin/bash

# Krijo sock directory (opsionale në disa raste)
mkdir -p /run/php && touch /run/php/php-fpm.sock

# Starto supervisord
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf