#!/usr/bin/env bash

./artisan migrate
./artisan horizon &
/var/www/artisan serve --host 0.0.0.0 --port 9000
