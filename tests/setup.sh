#!/bin/bash

set -e

echo -e "\n\e[94m[info] Install dev dependencies tools \e[0m\n"
apt-get update >/dev/null
apt-get install -y -qq --no-install-recommends \
  unzip \
  git \
  sqlite3 >/dev/null

echo -e "\n\e[94m[info] Set PHP.ini settings \e[0m\n"
echo 'memory_limit = -1' >>/usr/local/etc/php/conf.d/docker-php-memlimit.ini

echo -e "\n\e[94m[info] Install composer \e[0m\n"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
composer --version