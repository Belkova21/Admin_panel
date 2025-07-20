FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    libicu-dev libexif-dev sqlite3 libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_sqlite zip gd intl exif

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN if [ ! -f .env ]; then cp .env.example .env && php artisan key:generate --force; fi

RUN mkdir -p database && \
    if [ ! -f database/database.sqlite ]; then \
        touch database/database.sqlite; \
    fi

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

RUN php artisan storage:link || true

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000
