#!/bin/bash

# Exit when any command fails.
set -e

PHP_VERSION=${PHP_VERSION:='7.4'}
DRUPAL_CORE_CONSTRAINT="${DRUPAL_CORE_CONSTRAINT:=~9.3.0}"

echo -e "\n\e[94m[info] Run tests with PHP $PHP_VERSION for Drupal $DRUPAL_CORE_CONSTRAINT \e[0m\n"
docker run -it --rm -v "$PWD":/var/www/html/robo-drupal -w /var/www/html/robo-drupal \
  --env DRUPAL_CORE_CONSTRAINT=$DRUPAL_CORE_CONSTRAINT \
  drupal:php${PHP_VERSION}-fpm-buster \
  /bin/bash -c "./tests/setup.sh && ./tests/test.sh"