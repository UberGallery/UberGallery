FROM php:7.2-fpm
MAINTAINER Chris Kankiewicz <Chris@ChrisKankiewicz.com>

# Install dependencies
RUN apt-get update && apt-get install -y libmagickwand-dev libmemcached-dev libyaml-dev python unzip zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP extensions
RUN pecl install apcu && docker-php-ext-enable apcu \
    && pecl install imagick && docker-php-ext-enable imagick \
    && pecl install memcached && docker-php-ext-enable memcached \
    && pecl install redis && docker-php-ext-enable redis \
    && pecl install yaml && docker-php-ext-enable yaml

# Create php.ini
COPY files/php.ini /usr/local/etc/php/php.ini
