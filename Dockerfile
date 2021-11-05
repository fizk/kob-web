FROM php:8.0.12-apache-bullseye

EXPOSE 80
EXPOSE 443
ARG ENV
ARG IDE_KEY

RUN echo "memory_limit = 2048M\n \
post_max_size = 2048M\n \
upload_max_filesize = 2048M\n \
date.timezone = Atlantic/Reykjavik\n \
expose_php = Off \n" >> /usr/local/etc/php/php.ini

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

WORKDIR /var/www
RUN mkdir image-cache

COPY ./composer.json .
COPY ./composer.lock .

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

COPY ./bin ./bin
COPY ./config ./config
COPY ./public ./html
COPY ./src ./src
COPY ./templates ./templates












# FROM php:7.4.10-apache-buster

# ARG ENV

# EXPOSE 80
# EXPOSE 443

# RUN echo "memory_limit = 2048M \n \
# post_max_size = 2048M \n \
# upload_max_filesize = 2048M \n \
# date.timezone = Atlantic/Reykjavik \n \
# expose_php = Off \n" > /usr/local/etc/php/php.ini

# RUN if [ "$ENV" != "production" ] ; then \
#  echo "<VirtualHost *:80>\n \
#     ServerAdmin fizk78@gmail.com\n \
#     DocumentRoot /var/www/html\n \
#     ErrorLog \${APACHE_LOG_DIR}/error.log\n \
#     CustomLog \${APACHE_LOG_DIR}/access.log combined\n \
# </VirtualHost>" > /etc/apache2/sites-available/000-default.conf; \
# fi ;

# # RUN if [ "$ENV" = "production" ] ; then \
# # echo "<VirtualHost *:80>\n \
# #     ServerAdmin fizk78@gmail.com\n \
# #     DocumentRoot /var/www/html\n \
# #     ErrorLog \${APACHE_LOG_DIR}/error.log\n \
# #     CustomLog \${APACHE_LOG_DIR}/access.log combined\n \
# #     RewriteEngine on\n \
# #     RewriteCond %{SERVER_NAME} =www.klingogbang.is [OR]\n \
# #     RewriteCond %{SERVER_NAME} =klingogbang.is\n \
# #     RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent]\n \
# # </VirtualHost>" > /etc/apache2/sites-available/000-default.conf; \
# # echo "<IfModule mod_ssl.c>\n \
# #     <VirtualHost *:443>\n \
# #         ServerAdmin fizk78@gmail.com\n \
# #         DocumentRoot /var/www/html\n \
# #         ErrorLog \${APACHE_LOG_DIR}/error.log\n \
# #         CustomLog \${APACHE_LOG_DIR}/access.log combined\n \
# #         SSLCertificateFile /etc/letsencrypt/live/klingogbang.is/cert.pem\n \
# #         SSLCertificateKeyFile /etc/letsencrypt/live/klingogbang.is/privkey.pem\n \
# #         Include /etc/letsencrypt/options-ssl-apache.conf\n \
# #         SSLCertificateChainFile /etc/letsencrypt/live/klingogbang.is/chain.pem\n \
# #     </VirtualHost>\n \
# # </IfModule>" >/etc/apache2/sites-available/000-default-le-ssl.conf;\
# # ln -s /etc/apache2/sites-available/000-default-le-ssl.conf /etc/apache2/sites-enabled/000-default-le-ssl.conf; \
# # fi ;

# RUN apt-get update; \
#     apt-get install -y --no-install-recommends \
#         zip \
#         unzip \
#         git \
#         vim \
#         g++ \
#         openssl \
#         imagemagick \
#         libzip-dev \
#         libmagickwand-dev \
#         libmagickcore-dev \
#         zlib1g-dev \
#         libssl-dev \
#         libcurl4-openssl-dev; \
#     docker-php-ext-install zip; \
#     docker-php-ext-install pdo_mysql; \
#     docker-php-ext-install bcmath; \
#     docker-php-ext-install sockets; \
#     pecl install -o -f imagick-3.4.4; \
#     rm -rf /tmp/pear; \
#     docker-php-ext-enable imagick; \
#     a2enmod rewrite; \
#     a2enmod ssl; \
#     service apache2 restart

# RUN if [ "$ENV" != "production" ] ; then \
#     pecl install xdebug; \
#     docker-php-ext-enable xdebug; \
#     echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
#     echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
#     echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
#     echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
#     fi ;

# WORKDIR /var/www
# RUN mkdir image-cache

# RUN curl -sS https://getcomposer.org/installer | php -- \
#     --install-dir=/usr/local/bin --filename=composer

# COPY ./composer.json .
# COPY ./composer.lock .

# RUN if [ "$ENV" != "production" ] ; then \
#     composer install --prefer-source --no-interaction \
#     && composer dump-autoload; \
#     fi ;

# RUN if [ "$ENV" = "production" ] ; then \
#     composer install --prefer-source --no-interaction --no-dev  -o \
#     && composer dump-autoload -o; \
#     fi ;

# COPY ./bin ./bin
# COPY ./config ./config
# COPY ./public ./html
# COPY ./src ./src
# COPY ./templates ./templates
