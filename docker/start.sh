#!/bin/bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder --force

service nginx start
php-fpm