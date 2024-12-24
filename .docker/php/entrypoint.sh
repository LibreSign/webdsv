#!/bin/sh

# Set uid of host machine
usermod --non-unique --uid "${HOST_UID}" www-data
groupmod --non-unique --gid "${HOST_GID}" www-data

if [ ! -d "vendor" ]; then
    composer install
    chown -R www-data:www-data var
fi
php-fpm