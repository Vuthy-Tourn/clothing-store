#!/bin/sh

# Laravel setup
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

# Start FrankenPHP as main process
# Serve everything on 0.0.0.0:8080
frankenphp -S 0.0.0.0:8080 -c /etc/frankenphp/frankenphp.ini
