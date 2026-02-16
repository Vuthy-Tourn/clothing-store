#!/bin/sh

# Unzip storage if it exists
if [ -f public.zip ]; then
    unzip -o public.zip -d storage/app/
fi

# Laravel setup
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

# Start FrankenPHP
frankenphp -S 0.0.0.0:8080 -c /etc/frankenphp/frankenphp.ini
