FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
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
    mbstring

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8080

# HAPUS php artisan serve, GANTI DENGAN INI:
CMD sh -c "php -S 0.0.0.0:${PORT:-8080} -t public/"