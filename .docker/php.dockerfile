FROM php:7.4-fpm-alpine

COPY ./.docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./composer.json /var/www/composer.json
COPY ./composer.lock /var/www/composer.lock

RUN addgroup -g 1000 ezapi && adduser -G ezapi -g ezapi -s /bin/sh -D ezapi

RUN mkdir -p /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN chown ezapi:ezapi /var/www/html

WORKDIR /var/www/html