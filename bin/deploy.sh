#!/bin/bash
set -e

echo "==> Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

echo "==> Clearing cache..."
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod

echo "==> Deploy complete!"
