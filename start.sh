#!/bin/sh

# Laravel setup
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

# Start FrankenPHP in /tmp (Railway-safe)
frankenphp -c /etc/frankenphp/frankenphp.ini -S /tmp/frankenphp.sock &

# Wait for the socket to be ready
sleep 3

# Start Caddy
caddy run --config Caddyfile --adapter caddyfile
