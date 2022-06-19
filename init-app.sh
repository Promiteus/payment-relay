#!/usr/bin/env sh
echo 'Updating environment variables for docker...'
cp src/.env .env
echo 'Clearing cache...'
docker exec -it  pay-php-fpm bash -c "php artisan cache:clear"
echo 'Clearing config...'
docker exec -it  pay-php-fpm bash -c "php artisan config:clear"
echo 'Installing new dependencies...'
docker exec -it  pay-php-fpm bash -c "composer install"
echo 'Creating new migrations...'
docker exec -it  pay-php-fpm bash -c "php artisan migrate"
