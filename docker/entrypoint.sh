#!/usr/bin/env bash

[ ! -d "/var/www/storage/app" ] && cp -r /opt/storage/* /var/www/storage/

./artisan migrate
./artisan horizon &
exec php-fpm -c /etc/php7/php.ini &
#/var/www/artisan serve --host 0.0.0.0 --port 9000
nginx -g "daemon off;"

