#!/bin/bash
set -e

PORT="${PORT:-8080}"

# Configure Apache to listen on Railway's PORT
sed -i "s/Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/*.conf

# Fix MPM conflict — remove event, keep only prefork (required for mod_php)
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.*

# Run migrations first — tables must exist before anything else
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration 2>&1

# Seed data on first run (check if user table is empty)
if [ -f /app/docker/seed.sql ]; then
    USER_COUNT=$(psql "$DATABASE_URL" -tAc "SELECT COUNT(*) FROM \"user\"" 2>/dev/null || echo "0")
    if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
        echo "==> Empty database, importing seed data..."
        psql "$DATABASE_URL" --single-transaction -v ON_ERROR_STOP=1 < /app/docker/seed.sql 2>&1 || true
        echo "==> Seed data imported!"
        echo "==> Recalculating points for all tournaments..."
        php bin/console app:recalculate-points --all 2>&1 || true
    fi
fi

# Clear and rebuild cache with real env vars
php bin/console cache:clear 2>&1 || true
php bin/console cache:warmup 2>&1 || true

exec apache2-foreground
