FROM php:8.4-cli

ENV BUILD_VERSION=2

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    gd \
    zip \
    mbstring \
    intl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8080

CMD sh -c "php -S 0.0.0.0:${PORT:-8080} -t public/"