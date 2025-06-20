FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
  libfreetype-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  libpq-dev \
  libcurl4-openssl-dev \
  libonig-dev \
  msmtp \
  msmtp-mta \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd \
  && docker-php-ext-install pgsql pdo_pgsql curl fileinfo mbstring exif

COPY msmtprc /etc/msmtprc

RUN touch /var/log/msmtp.log && \
  chmod 666 /var/log/msmtp.log

RUN a2enmod rewrite

COPY /app /var/www/html/

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY php.ini $PHP_INI_DIR/conf.d/

COPY apache2.conf /etc/apache2/conf-available/servername.conf

RUN a2enconf servername

EXPOSE 80
