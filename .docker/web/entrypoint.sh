#!/bin/sh
cd /var/www/html
if [ ! -d "vendor" ]; then
    composer install
fi
php-fpm