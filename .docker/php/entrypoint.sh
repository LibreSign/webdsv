#!/bin/sh
if [ ! -d "vendor" ]; then
    composer install
    chown -R www-data:www-data var
fi
php-fpm