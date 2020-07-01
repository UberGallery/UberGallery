FROM php:7.4-apache
LABEL maintainer="Chris Kankiewicz <Chris@ChrisKankiewicz.com>"

RUN apt-get update && apt-get install --assume-yes libmagickwand-dev libmemcached-dev \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install imagick memcached redis xdebug \
    && docker-php-ext-enable imagick memcached redis xdebug

RUN a2enmod rewrite

COPY .docker/apache/config/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/php/config/php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www/html
