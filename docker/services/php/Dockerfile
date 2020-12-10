FROM php:7.4-fpm-alpine

RUN apk --update --no-cache add git curl bash

RUN set -ex && apk --no-cache add postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql bcmath

WORKDIR /var/www

RUN apk add --no-cache \
    git \
    yarn \
    autoconf \
    g++ \
    make \
    openssl-dev

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

COPY project.ini /usr/local/etc/php/conf.d/project.ini

EXPOSE 9000
