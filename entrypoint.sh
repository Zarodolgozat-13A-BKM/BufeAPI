#!/bin/sh
set -e
ulimit -n 65535
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

cron -f
php artisan reverb:start --debug & php artisan serve --host=0.0.0.0 --port=8000
