FROM php:7.3-apache

RUN apt-get update \
 && apt-get install -y zip libzip-dev \
 && apt-get install -y libmagickwand-dev libmagickcore-dev \
 && apt-get install -y zip unzip \
 && apt-get install -y imagemagick \
 && apt-get install -y git zlib1g-dev vim \
 && apt-get install -y autoconf g++ make openssl libssl-dev libcurl4-openssl-dev pkg-config libsasl2-dev libpcre3-dev \
 && docker-php-ext-install zip \
 && docker-php-ext-install pdo_mysql \
 && docker-php-ext-install bcmath \
 && docker-php-ext-install sockets \
 && a2enmod rewrite \
 && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/apache2.conf \
 && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
 && mv /var/www/html /var/www/public

COPY ./auto/server/apache2.conf /etc/apache2/sites-available/000-default.conf
COPY ./auto/server/apache2-le-ssl.conf /etc/apache2/sites-available/000-default-le-ssl.conf

RUN ln -s /etc/apache2/sites-available/000-default-le-ssl.conf /etc/apache2/sites-enabled/000-default-le-ssl.conf

RUN curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install -o -f imagick-3.4.4 \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable imagick

COPY ./auto/php/php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www

COPY ./composer.json .
COPY ./composer.lock .
#COPY ./phpcs.xml .
#COPY ./phpunit.xml.dist .

RUN /usr/local/bin/composer install --prefer-source --no-interaction \
    && /usr/local/bin/composer dump-autoload -o

EXPOSE 80


