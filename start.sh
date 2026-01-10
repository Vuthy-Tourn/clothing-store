#!/bin/sh

# Laravel setup
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

# Start FrankenPHP in the foreground on TCP port 9000
frankenphp -c /etc/frankenphp/frankenphp.ini -S 0.0.0.0:9000
