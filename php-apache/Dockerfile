FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
  libfreetype-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  libpq-dev \
  libcurl4-openssl-dev \
  libonig-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd \
  && docker-php-ext-install pgsql pdo_pgsql curl fileinfo mbstring exif

COPY /src /var/www/html/

# COPY php.ini /usr/local/etc/php/conf.d/

EXPOSE 80
