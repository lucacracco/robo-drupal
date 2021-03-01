#!/bin/bash

set -e

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

# Print version of composer
composer --version