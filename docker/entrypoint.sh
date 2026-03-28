#!/bin/bash
set -e

PORT="${PORT:-8080}"

# Configure Apache to listen on Railway's PORT
sed -i "s/Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/*.conf

# Fix MPM conflict — remove event, keep only prefork (required for mod_php)
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.*

# Run migrations on startup
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration 2>&1 || true

# Seed data on first run (check if user table is empty)
USER_COUNT=$(php bin/console dbal:run-sql "SELECT COUNT(*) FROM \"user\"" 2>/dev/null | grep -oP '\d+' | tail -1 || echo "0")
if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo "==> Empty database, importing seed data..."
    php bin/console dbal:run-sql "$(cat /app/docker/seed.sql)" 2>&1 || true
    echo "==> Seed data imported!"
fi

# Build assets and warm cache (runs with real env vars from Railway)
composer run-script post-install-cmd 2>&1 || true
php bin/console tailwind:build 2>&1 || true
php bin/console asset-map:compile 2>&1 || true
php bin/console cache:clear 2>&1 || true
php bin/console cache:warmup 2>&1 || true

exec apache2-foreground
