#!/bin/sh
set -e

php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

php artisan serve --host=0.0.0.0 --port=8000
