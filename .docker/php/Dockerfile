FROM php:8.2-fpm as prod
RUN apt-get update && \
    apt-get install -y \
        git \
        poppler-utils \
        poppler-data \
        libopenjp2-7-dev \
        libfreetype6-dev \
        libfontconfig1-dev \
        libjpeg-dev \
        libtiff5-dev \
        libnss3-dev \
        zip

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT ["sh", "/usr/local/bin/entrypoint.sh"]

FROM prod as dev

COPY config/php.ini /usr/local/etc/php/conf.d/

RUN pecl install xdebug-3.2.0 \
    && docker-php-ext-enable xdebug
