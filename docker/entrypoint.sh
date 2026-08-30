#!/usr/bin/env bash
set -e

cd /var/www/html

echo "=== DATABASE CONFIG ==="
echo "DB_CONNECTION=$DB_CONNECTION"
echo "DB_HOST=$DB_HOST"
echo "DB_PORT=$DB_PORT"
echo "DB_DATABASE=$DB_DATABASE"
echo "DB_USERNAME=$DB_USERNAME"
echo "======================="

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

php artisan config:clear

mysql \
    -h "$DB_HOST" \
    -P "$DB_PORT" \
    -u "$DB_USERNAME" \
    -p"$DB_PASSWORD" \
    "$DB_DATABASE" \
    -e "SELECT 1;"

php artisan migrate --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

php-fpm -D
nginx -g "daemon off;"