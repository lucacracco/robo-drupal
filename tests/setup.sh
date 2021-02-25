#!/bin/bash

set -e

DRUPAL_CORE_CONSTRAINT="${DRUPAL_CORE_CONSTRAINT:=8.8.12}"

apt-get update
apt-get install -y --no-install-recommends \
  libfreetype6-dev \
  libjpeg-dev \
  libpng-dev \
  libpq-dev \
  libzip-dev \
  unzip \
  git \
  sqlite3

#docker-php-ext-configure gd --with-freetype --with-jpeg=/usr

docker-php-ext-install -j "$(nproc)" \
  gd \
  opcache \
  pdo_mysql \
  pdo_pgsql \
  zip

# Set PHP.ini settings
echo 'memory_limit = -1' >>/usr/local/etc/php/conf.d/docker-php-memlimit.ini

# Install composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Rollback to composer1 for Drupal 8.8.12
echo "\n\nDrupal core constraint: $DRUPAL_CORE_CONSTRAINT\n\n"
if [[ "$DRUPAL_CORE_CONSTRAINT" == "8.8.12" ]]; then
  composer self-update --1
  composer global require hirak/prestissimo
fi

# Print version of composer
composer --version
