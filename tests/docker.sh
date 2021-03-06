#!/bin/bash

# Exit when any command fails.
set -e

PHP_VERSION=${PHP_VERSION:='7.2-fpm-buster'}
DRUPAL_CORE_CONSTRAINT="${DRUPAL_CORE_CONSTRAINT:=8.8.12}"

echo -e "Run tests with PHP $PHP_VERSION for Drupal $DRUPAL_CORE_CONSTRAINT\n"
docker run -it --rm -v "$PWD":/var/www/html/robo-drupal -w /var/www/html/robo-drupal \
  --env DRUPAL_CORE_CONSTRAINT=$DRUPAL_CORE_CONSTRAINT \
  php:$PHP_VERSION \
  /bin/bash
#  -c "./tests/setup.sh && ./tests/test.sh"
