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

COPY app/ /var/www/html/

RUN a2enmod rewrite
COPY apache2.conf /etc/apache2/conf-available/servername.conf
RUN a2enconf servername

RUN echo "upload_max_filesize = 10M" >> $PHP_INI_DIR/php.ini && \
    echo "post_max_size = 10M" >> $PHP_INI_DIR/php.ini
COPY php.ini $PHP_INI_DIR/conf.d/

COPY msmtprc /etc/msmtprc

EXPOSE 80
WORKDIR /var/www/html
