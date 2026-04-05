FROM php:8.3-apache

# PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
        libicu-dev \
        libzip-dev \
        libgd-dev \
        unzip \
        git \
        postgresql-client \
    && docker-php-ext-configure gd \
    && docker-php-ext-install \
        pdo_pgsql \
        intl \
        opcache \
        zip \
        gd \
    && rm -rf /var/lib/apt/lists/*

# Apache config
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT=/app/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && sed -ri -e 's!<Directory /var/www/>!<Directory ${APACHE_DOCUMENT_ROOT}>!' /etc/apache2/apache2.conf \
    && sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Pass Railway env vars through Apache to PHP (mod_php doesn't inherit them by default)
RUN echo "PassEnv APP_ENV APP_SECRET DATABASE_URL" > /etc/apache2/conf-enabled/passenv.conf

# PHP production config
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php-prod.ini $PHP_INI_DIR/conf.d/app.ini

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Set prod env for build and runtime
ENV APP_ENV=prod

# Install dependencies (layer cache)
COPY composer.json composer.lock symfony.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy app source (.env is included — provides fallback values for build-time commands)
COPY . .

# Build assets at build time (no DB connection needed, .env provides dummy DATABASE_URL)
RUN composer run-script post-install-cmd \
    && php bin/console tailwind:build \
    && php bin/console asset-map:compile

# Create var/ (excluded by .dockerignore) and set permissions
RUN mkdir -p var && chown -R www-data:www-data var/

# Entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080

CMD ["entrypoint.sh"]
