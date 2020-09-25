FROM php:7.4.10-apache-buster

ARG ENV

EXPOSE 80

COPY ./auto/php/php.ini /usr/local/etc/php/php.ini

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
        libcurl4-openssl-dev; \
    docker-php-ext-install zip; \
    docker-php-ext-install pdo_mysql; \
    docker-php-ext-install bcmath; \
    docker-php-ext-install sockets; \
    pecl install -o -f imagick-3.4.4; \
    rm -rf /tmp/pear; \
    docker-php-ext-enable imagick; \
    a2enmod rewrite; \
    a2enmod ssl; \
    service apache2 restart

RUN if [ "$ENV" != "production" ] ; then \
    pecl install xdebug; \
    docker-php-ext-enable xdebug; \
    echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    fi ;

WORKDIR /var/www

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

COPY ./composer.json .
COPY ./composer.lock .

RUN if [ "$ENV" != "production" ] ; then \
    composer install --prefer-source --no-interaction --no-suggest \
    && composer dump-autoload; \
    fi ;

RUN if [ "$ENV" = "production" ] ; then \
    composer install --prefer-source --no-interaction --no-dev --no-suggest -o \
    && composer dump-autoload -o; \
    fi ;

COPY ./config ./config
COPY ./public ./html
COPY ./src ./src
COPY ./templates ./templates
