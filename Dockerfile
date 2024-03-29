#
# Assets Image
#
# Pre-Compile all assets
# They will later be copied into the main image
#

FROM node:14-bullseye as assets

WORKDIR /app

COPY ./package.json ./package.json
COPY ./package-lock.json ./package-lock.json
COPY ./postcss.config.js ./postcss.config.js
COPY ./public/styles/ ./public/styles/

RUN npm install; \
    ./node_modules/.bin/postcss ./public/styles/styles.css -o ./styles.css


#
# Main Image
#
# Builds the main PHP/Apache image
# This images will then copy assets from previous build-steps
#

FROM php:8.0.12-apache-bullseye

EXPOSE 80

ARG ENV
ARG IDE_KEY

RUN echo "memory_limit = 2048M\n \
post_max_size = 2048M\n \
upload_max_filesize = 2048M\n \
date.timezone = Atlantic/Reykjavik\n \
expose_php = Off \n\n \
opcache.enable=1\n \
opcache.jit_buffer_size=100M\n \
opcache.jit=1255" >> /usr/local/etc/php/php.ini

RUN apt-get update; \
    apt-get install -y --no-install-recommends \
        zip \
        unzip \
        git \
        vim \
        g++ \
        openssl \
        imagemagick \
        libzip-dev \
        libmagickwand-dev \
        libmagickcore-dev \
        zlib1g-dev \
        libssl-dev \
        libcurl4-openssl-dev \
        locales \
        locales-all; \
    docker-php-ext-install zip; \
    docker-php-ext-install opcache; \
    docker-php-ext-install pdo_mysql; \
    docker-php-ext-install bcmath; \
    docker-php-ext-install sockets; \
    pecl install -o -f imagick-3.5.1; \
    rm -rf /tmp/pear; \
    docker-php-ext-enable imagick; \
    a2enmod rewrite; \
    a2enmod ssl; \
    service apache2 restart

RUN if [ "$ENV" = "development" ] ; then \
    pecl install xdebug; \
    docker-php-ext-enable xdebug; \
    echo "\n[xdebug]\n\
xdebug.mode=develop,debug\n\
xdebug.client_host=host.docker.internal\n\
xdebug.start_with_request=yes\n\
xdebug.client_port=9003\n\
xdebug.idekey=$IDE_KEY\n \
xdebug.discover_client_host=false\n" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
fi ;

RUN chown -R www-data:www-data /var/www
WORKDIR /var/www
USER www-data

COPY --chown=www-data:www-data ./composer.json ./composer.json
COPY --chown=www-data:www-data ./composer.lock ./composer.lock

RUN mkdir image-cache
RUN mkdir -p data/cache
RUN mkdir -p html/img

RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/var/www --filename=composer --version=2.1.3

RUN if [ "$ENV" = "development" ] ; then \
    /var/www/composer install --prefer-source --no-interaction --no-cache; \
    /var/www/composer dump-autoload; \
fi ;

RUN if [ "$ENV" != "development" ] ; then \
    /var/www/composer install --prefer-source --no-interaction --no-dev --no-cache -o; \
    /var/www/composer dump-autoload -o; \
fi ;

COPY --chown=www-data:www-data ./bin ./bin
COPY --chown=www-data:www-data ./config ./config
COPY --chown=www-data:www-data ./public/favicon ./html/favicon
COPY --chown=www-data:www-data ./public ./html
COPY --from=assets --chown=www-data:www-data /app/styles.css /var/www/html/styles/styles.css
COPY --chown=www-data:www-data ./src ./src
COPY --chown=www-data:www-data ./templates ./templates

RUN if [ "$ENV" != "development" ] ; then \
    ./bin/precompile.sh; \
fi ;
