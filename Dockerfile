FROM php:8.2-cli

WORKDIR /app

# system dependencies + php extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# copy project
COPY . .

# install composer
RUN curl -sS https://getcomposer.org/installer | php

# install dependencies
RUN php composer.phar install --no-dev --optimize-autoloader

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000