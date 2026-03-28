#!/bin/bash
set -e

PORT="${PORT:-8080}"

# Configure Apache to listen on Railway's PORT
sed -i "s/Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/*.conf

# Fix MPM conflict — remove event, keep only prefork (required for mod_php)
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.*

# Run migrations on startup (safe — skips if already up to date)
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration 2>&1 || true

# Warm cache if needed
php bin/console cache:warmup 2>&1 || true

exec apache2-foreground
