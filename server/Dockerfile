FROM php:apache

RUN apt-get update && \
    apt-get install -y
RUN apt-get install -y curl
RUN apt-get install -y build-essential libssl-dev zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev
RUN apt-get install -y libicu-dev libpq-dev libzip-dev libonig-dev

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

RUN docker-php-ext-install pgsql pdo pdo_pgsql zip intl
RUN docker-php-ext-configure intl

COPY ports.conf /etc/apache2/ports.conf
COPY server.conf /etc/apache2/sites-available/000-default.conf
COPY composer.json /var/www/html/composer.json
COPY composer.lock /var/www/html/composer.lock

RUN a2enmod rewrite
RUN service apache2 restart

WORKDIR /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install