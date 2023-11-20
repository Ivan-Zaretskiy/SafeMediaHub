FROM php:8.2-fpm

RUN apt-get update \
    && apt-get install -y \
      libmemcached-dev \
      zlib1g-dev \
      libzip-dev \
      libpq-dev \
      libssl-dev \
      libnghttp2-dev \
      libmemcached-dev \
    && pecl install memcached \
    && docker-php-ext-enable memcached \
    && docker-php-ext-install pdo pdo_mysql zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && pecl install memcache \
    && docker-php-ext-enable memcache

WORKDIR /var/www/html

COPY conf.d/* $PHP_INI_DIR/conf.d/

COPY --from=composer /usr/bin/composer /usr/bin/composer

EXPOSE 9000
CMD ["php-fpm"]