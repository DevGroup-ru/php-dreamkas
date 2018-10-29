#!/usr/bin/env bash

#docker run -v `pwd`:/var/www/html cespi/php-5.3:modules-cli-latest sh -c "cd /var/www/html/ && composer install"
docker run -v `pwd`:/var/www/html cespi/php-5.3:modules-cli-latest sh -c "cd /var/www/html/ && php vendor/bin/phpunit"