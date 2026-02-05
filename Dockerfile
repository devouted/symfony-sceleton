FROM php:8.3-apache

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip opcache \
    && pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN a2enmod rewrite

WORKDIR /var/www/html

RUN groupadd -g ${GROUP_ID} symfony || true \
    && useradd -u ${USER_ID} -g symfony -m symfony || true \
    && chown -R www-data:www-data /var/www/html

EXPOSE 80
