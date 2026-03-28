FROM php:8.3-apache

# PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
        libicu-dev \
        libzip-dev \
        unzip \
        git \
    && docker-php-ext-install \
        pdo_pgsql \
        intl \
        opcache \
        zip \
    && rm -rf /var/lib/apt/lists/*

# Apache config
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT=/app/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && sed -ri -e 's!<Directory /var/www/>!<Directory ${APACHE_DOCUMENT_ROOT}>!' /etc/apache2/apache2.conf \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Allow .htaccess overrides
RUN sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

# Railway sets PORT dynamically via env var
RUN sed -ri -e 's!Listen 80!Listen ${PORT}!g' /etc/apache2/ports.conf \
    && sed -ri -e 's!:80>!:${PORT}>!g' /etc/apache2/sites-available/*.conf

# PHP production config
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php-prod.ini $PHP_INI_DIR/conf.d/app.ini

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Install dependencies first (better layer caching)
COPY composer.json composer.lock symfony.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy app source
COPY . .

# Run post-install scripts, build assets, warm cache
RUN composer run-script post-install-cmd \
    && php bin/console tailwind:build --env=prod \
    && php bin/console asset-map:compile --env=prod \
    && php bin/console cache:clear --env=prod \
    && php bin/console cache:warmup --env=prod \
    && chown -R www-data:www-data var/

ENV PORT=8080
EXPOSE 8080

CMD ["apache2-foreground"]
