#!/bin/bash
set -e

# Railway injects PORT env var — configure Apache to listen on it
PORT="${PORT:-8080}"

sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/*.conf

exec apache2-foreground
