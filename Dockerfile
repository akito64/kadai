FROM php:8.4-fpm-alpine AS php

RUN docker-php-ext-install pdo_mysql

RUN apk add --no-cache \
    curl-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    autoconf \
    g++ \
    make \
    build-base \
    bash\
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd exif curl

RUN pecl install apcu \
    && docker-php-ext-enable apcu


RUN install -o www-data -g www-data -d /var/www/upload/image/

RUN echo -e "post_max_size = 5M\nupload_max_filesize = 5M" >> ${PHP_INI_DIR}/php.ini
