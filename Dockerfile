FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite

WORKDIR /var/www/html

COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
