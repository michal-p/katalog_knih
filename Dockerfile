# 1. Base stage with PHP, Apache extensions, and Composer
FROM php:8.2-apache AS base

RUN apt-get update && apt-get upgrade -y && apt-get install -y \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# 2. Development stage (uses bind mount volumes from docker-compose.override.yml)
FROM base AS dev
# No file copying needed here because local files will be mounted

# 3. Production stage (embeds application source code and installs dependencies)
FROM base AS prod
COPY . /var/www/html
# Set up production environment (composer install without dev dependencies)
RUN composer install --no-dev --optimize-autoloader
