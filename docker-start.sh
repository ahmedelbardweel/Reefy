#!/bin/bash

# Use Railway's PORT env variable (default to 80)
PORT=${PORT:-80}

# Update Apache to listen on the correct port
sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf
sed -i "s/:80>/:$PORT>/" /etc/apache2/sites-available/000-default.conf

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run migrations
php artisan migrate --force

# Link storage
php artisan storage:link || true

# Clear and cache config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting Apache on port $PORT..."
apache2-foreground
