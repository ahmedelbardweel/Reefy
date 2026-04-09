#!/bin/bash
set -e

# Use Railway's PORT env variable (default to 80)
PORT=${PORT:-80}

echo "==> Configuring Apache on port $PORT..."
sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf
sed -i "s/:80>/:$PORT>/" /etc/apache2/sites-available/000-default.conf

echo "==> Running Laravel optimizations..."

# Cache config/routes/views (don't crash if it fails)
php artisan config:cache || echo "Warning: config:cache failed"
php artisan route:cache  || echo "Warning: route:cache failed"
php artisan view:cache   || echo "Warning: view:cache failed"

# Run migrations only if DB is configured
if [ -n "$DB_HOST" ]; then
    echo "==> Running migrations..."
    php artisan migrate --force || echo "Warning: migration failed, continuing..."
    php artisan storage:link    || echo "Warning: storage:link failed"
else
    echo "==> No DB_HOST set, skipping migrations."
fi

echo "==> Starting Apache on port $PORT..."
exec apache2-foreground
