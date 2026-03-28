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
    && sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# PHP production config
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php-prod.ini $PHP_INI_DIR/conf.d/app.ini

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Set prod env BEFORE anything Symfony-related
ENV APP_ENV=prod
ENV APP_SECRET=build-time-placeholder
ENV DATABASE_URL="postgresql://dummy:dummy@localhost:5432/dummy?serverVersion=16"

# Install dependencies (layer cache)
COPY composer.json composer.lock symfony.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy app source
COPY . .

# Compile .env for prod (bypasses dotenv, uses real env vars)
RUN composer dump-env prod

# Build assets and warm cache
RUN composer run-script post-install-cmd \
    && php bin/console tailwind:build \
    && php bin/console asset-map:compile \
    && php bin/console cache:clear \
    && php bin/console cache:warmup \
    && chown -R www-data:www-data var/

# Entrypoint: set Apache port from Railway's PORT env var, then start
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080

CMD ["entrypoint.sh"]
