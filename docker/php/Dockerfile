FROM php:7.4-fpm-alpine

ARG XDEBUG_VERSION=2.9.5

RUN set -xe \
        && apk add --no-cache --virtual .build-deps \
            $PHPIZE_DEPS \
            libmemcached-dev \
            libxml2-dev \
            rabbitmq-c-dev \
            libzip-dev \
    && apk add --no-cache \
                libmemcached \
                rabbitmq-c \
                bash \
                libzip-dev \
                libpng-dev \
    && pecl install memcached \
    && pecl install amqp \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable memcached \
    && docker-php-ext-enable amqp \
    && docker-php-ext-install sockets \
    && pecl install xdebug-${XDEBUG_VERSION} \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install gd \
    && docker-php-ext-install opcache \
    && pecl clear-cache \
    && apk del .build-deps

RUN echo 'xdebug.remote_autostart=true' >> /usr/local/etc/php/php.ini && \
    echo 'xdebug.remote_mode=req' >> /usr/local/etc/php/php.ini && \
    echo 'xdebug.remote_handler=dbgp' >> /usr/local/etc/php/php.ini && \
    echo 'xdebug.remote_connect_back=1 ' >> /usr/local/etc/php/php.ini && \
    echo 'xdebug.remote_port=9000' >> /usr/local/etc/php/php.ini && \
    echo 'xdebug.remote_enable=1' >> /usr/local/etc/php/php.ini && \
    echo 'xdebug.idekey=docker' >> /usr/local/etc/php/php.ini && \
    echo 'xdebug.remote_host=172.18.0.1' >> /usr/local/etc/php/php.ini && \
    echo 'upload_max_filesize=10M' >> /usr/local/etc/php/php.ini && \
    echo 'post_max_size=10M' >> /usr/local/etc/php/php.ini

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0"
ADD opcache.ini "$PHP_INI_DIR/conf.d/opcache.ini"

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /srv/botstory/current

CMD ["php-fpm"]
EXPOSE 9000
