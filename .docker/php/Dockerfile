FROM php:8.3-fpm
RUN apt-get update && \
    apt-get install -y \
        git \
        poppler-utils \
        poppler-data \
        zip

# Install PHP extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync \
    && install-php-extensions \
    @composer \
    xdebug \
    && rm /usr/local/bin/install-php-extensions

COPY config/php.ini /usr/local/etc/php/conf.d/
COPY config/* /usr/local/etc/php/conf.d/

WORKDIR /app

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT ["sh", "/usr/local/bin/entrypoint.sh"]
