#!/usr/bin/env bash
docker build -t php-dreamkas .
docker run -it php-dreamkas vendor/bin/phpunit