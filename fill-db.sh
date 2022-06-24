#!/usr/bin/env sh
echo 'filling table "products" ...'
docker exec -it  pay-php-fpm bash -c "php artisan db:seed --class=ProductsTableSeeder"
