#!/bin/sh

# Laravel setup
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

# Start FrankenPHP in the background
frankenphp -c /etc/frankenphp/frankenphp.ini &

# Wait a few seconds for the socket to be created
sleep 3

# Start Caddy
caddy run --config /Caddyfile --adapter caddyfile
