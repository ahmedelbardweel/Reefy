#!/bin/bash
set -e

PORT=${PORT:-8080}

echo "==> Configuring Nginx on port $PORT..."
sed -i "s/listen 8080;/listen $PORT;/" /etc/nginx/sites-available/default

echo "==> Running Laravel optimizations..."
php artisan config:cache  || echo "Warning: config:cache failed"
php artisan route:cache   || echo "Warning: route:cache failed"
php artisan view:cache    || echo "Warning: view:cache failed"

if [ -n "$DB_HOST" ]; then
    echo "==> Running migrations..."
    php artisan migrate --force    || echo "Warning: migration failed, continuing..."
    php artisan storage:link       || echo "Warning: storage:link failed"
else
    echo "==> No DB_HOST set, skipping migrations."
fi

echo "==> Starting PHP-FPM..."
php-fpm -D

echo "==> Starting Nginx on port $PORT..."
exec nginx -g "daemon off;"
