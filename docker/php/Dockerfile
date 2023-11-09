FROM dunglas/frankenphp:alpine

RUN install-php-extensions \
    pdo_mysql \
    xdebug \
    intl \
    zip \
    opcache \
    redis \
    sockets

RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer