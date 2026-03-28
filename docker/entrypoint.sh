#!/bin/bash
set -e

PORT="${PORT:-8080}"

# Configure Apache to listen on Railway's PORT
sed -i "s/Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/*.conf

# Debug: verify only one MPM is loaded
echo "==> MPM modules enabled:"
ls /etc/apache2/mods-enabled/mpm_* 2>/dev/null || echo "none"
echo "==> Apache config test:"
apache2ctl configtest 2>&1 || true
echo "==> Starting Apache on port ${PORT}"

exec apache2-foreground
