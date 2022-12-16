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
ARG host_uid=1000
ARG host_gid=1000

RUN pecl install xdebug-3.2.0 \
    && docker-php-ext-enable xdebug

RUN usermod --non-unique --uid ${host_uid} www-data \
  && groupmod --non-unique --gid ${host_gid} www-data

USER www-data

COPY config/php.ini /usr/local/etc/php/conf.d/
