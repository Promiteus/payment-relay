#!/usr/bin/env sh
echo 'filling table "categories" ...'
docker exec -it  pay-php-fpm bash -c "php artisan db:seed --class=CategoriesTableSeeder"
echo 'filling table "products" ...'
docker exec -it  pay-php-fpm bash -c "php artisan db:seed --class=ProductsTableSeeder"
echo 'filling table "users" ...'
docker exec -it  pay-php-fpm bash -c "php artisan db:seed --class=UsersTableSeeder"
