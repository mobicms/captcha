FROM php:8.3-fpm-alpine3.22

# Установка необходимых библиотек
RUN apk update && apk add --no-cache \
    libpng-dev \
    libwebp-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    unzip

# Установка необходимых расширений
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable gd

WORKDIR /app
