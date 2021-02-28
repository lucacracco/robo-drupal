#!/bin/bash

set -e

function version() { echo "$@" | awk -F. '{ printf("%d%03d%03d%03d\n", $1,$2,$3,$4); }'; }
DRUPAL_CORE_CONSTRAINT="${DRUPAL_CORE_CONSTRAINT:=~8.8.0}"

apt-get update >/dev/null
apt-get install -y -qq --no-install-recommends \
  libfreetype6-dev \
  libjpeg-dev \
  libpng-dev \
  libpq-dev \
  libzip-dev \
  unzip \
  git \
  sqlite3 >/dev/null

docker-php-ext-install -j "$(nproc)" \
  gd \
  opcache \
  pdo_mysql \
  pdo_pgsql \
  zip >/dev/null

# Set PHP.ini settings
echo 'memory_limit = -1' >>/usr/local/etc/php/conf.d/docker-php-memlimit.ini

# Install composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Rollback to composer1 for Drupal < 8.9
echo "\n\nDrupal core constraint: $DRUPAL_CORE_CONSTRAINT\n\n"
VERSION=$(echo $DRUPAL_CORE_CONSTRAINT | tr --delete \~)
if [ $(version "$VERSION") -lt $(version "8.9") ]; then
  composer self-update --1
  composer global require hirak/prestissimo
fi

# Print version of composer
composer --version
