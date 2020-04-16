#!/bin/sh

php artisan down
php artisan optimize:clear
php artisan queue:restart
php artisan queue:retry all
git pull
git composer update
php artisan up
