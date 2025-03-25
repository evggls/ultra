FROM php:8.4-fpm-alpine

WORKDIR /var/www/shop-api

RUN apk add --no-cache \
    iputils curl \
    bash \
    git \
    unzip \
    libzip-dev \
    mariadb-client \
    && docker-php-ext-install pdo pdo_mysql zip

RUN apk add --no-cache --virtual .build-deps \
    gcc g++ make autoconf libc-dev zlib-dev \
    openssl-dev curl-dev

RUN apk add --no-cache librdkafka-dev

RUN pecl install rdkafka \
    && docker-php-ext-enable rdkafka

RUN apk del .build-deps

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY .docker/php-fpm/shop-api/php.ini /usr/local/etc/php/conf.d/php.ini

COPY ./shop-api/ /var/www/shop-api

RUN composer install

CMD ["php-fpm"]