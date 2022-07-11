#!/usr/bin/env sh
echo "Запуск тестов..."
docker exec -it  pay-php-fpm bash -c "php artisan test"
