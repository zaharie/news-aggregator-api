#!/bin/bash

set -e


if [ ! -d "vendor" ]; then
    composer install
fi


if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate
fi


php artisan migrate --force


php artisan dispatch:fetch-articles

php artisan serve --host=0.0.0.0 --port=8000

