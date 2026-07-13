#!/usr/bin/env bash

echo "Generating .env file from environment variables"
cat > /var/www/html/.env << EOF
APP_NAME="${APP_NAME}"
APP_ENV=${APP_ENV}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG}
DB_CONNECTION=${DB_CONNECTION}
DATABASE_URL=${DATABASE_URL}
LOG_CHANNEL=${LOG_CHANNEL}
SESSION_DRIVER=database
EOF

echo "Running composer"
composer install --no-dev --working-dir=/var/www/html

echo "Clearing cached config (avoid stale settings)"
php artisan config:clear

echo "Running migrations..."
php artisan migrate --force