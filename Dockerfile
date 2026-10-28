FROM node:22-bookworm-slim AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY vite.config.js ./
RUN npm run build

FROM composer:2 AS dependencies

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader

FROM php:8.3-apache-bookworm AS app

RUN apt-get update && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libicu-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        intl \
        mbstring \
        opcache \
        pdo_mysql \
        zip \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN a2enmod rewrite \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/sites-available/*.conf \
        /etc/apache2/apache2.conf \
        /etc/apache2/conf-available/*.conf

COPY --from=dependencies /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build
COPY . .

RUN mkdir -p storage/framework/cache storage/framework/sessions \
        storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

EXPOSE 80

ENTRYPOINT ["entrypoint"]
CMD ["apache2-foreground"]
