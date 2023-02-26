#!/bin/bash

# Exit when any command fails.
set -e

if ! command -v composer &>/dev/null; then
  echo "Composer executable could not be found"
  exit
fi

echo -e "\n\e[94m[info] Directory running ${PWD} \e[0m\n"
FOLDER_REPO=${FOLDER_REPO:="${PWD}"}
FOLDER_TESTS=${FOLDER_TESTS:="${PWD}/../robo-drupal-demo"}
DRUPAL_CORE_CONSTRAINT="${DRUPAL_CORE_CONSTRAINT:=~9.4.0}"

echo -e "\n\e[94m[info] Clear/Create directory of install drupal for tests \e[0m\n"
rm -rf $FOLDER_TESTS
mkdir -p $FOLDER_TESTS
chmod 775 -R $FOLDER_TESTS
rm -Rf $FOLDER_TESTS

echo -e "\n\e[94m[info] Installing Drupal $DRUPAL_CORE_CONSTRAINT on $FOLDER_TESTS \e[0m\n"
composer create-project drupal/recommended-project:$DRUPAL_CORE_CONSTRAINT $FOLDER_TESTS --no-install
composer config allow-plugins true --working-dir=$FOLDER_TESTS
composer install --prefer-dist --no-interaction --working-dir=$FOLDER_TESTS

echo -e "\n\e[94m[info] Installing Requirements \e[0m\n"
composer require --no-interaction --working-dir=$FOLDER_TESTS \
  drupal/core-recommended:$DRUPAL_CORE_CONSTRAINT \
  drupal/core-dev:$DRUPAL_CORE_CONSTRAINT \
  drupal/core-composer-scaffold:$DRUPAL_CORE_CONSTRAINT \
  drupal/core:$DRUPAL_CORE_CONSTRAINT

echo -e "\n\e[94m[info] Installing RoboDrupal: set custom repository for RoboDrupal \e[0m\n"
composer config --working-dir=$FOLDER_TESTS repositories.1 "{\"type\": \"path\", \"url\": \"$FOLDER_REPO\", \"symlink\": true}"
composer require --no-interaction --working-dir=$FOLDER_TESTS lucacracco/robo-drupal:@dev

echo -e "\n\e[94m[info] Copy template settings \e[0m\n"
cp -v "$FOLDER_REPO/tests/template/tpl.settings.php" "$FOLDER_TESTS/web/sites/default/tpl.settings.php"
cp -v "$FOLDER_REPO/tests/template/tpl.services.yml" "$FOLDER_TESTS/web/sites/default/tpl.services.yml"
cp -v "$FOLDER_REPO/tests/template/robo.yml" "$FOLDER_TESTS/robo.yml"
cp -v "$FOLDER_REPO/tests/template/RoboFile.php" "$FOLDER_TESTS/RoboFile.php"

echo -e "\n\e[94m[info] Open folder test: $FOLDER_TESTS \e[0m\n"
cd "$FOLDER_TESTS"

# Debug
#echo -e "\n\e[94m[info] Check symlink from $FOLDER_TESTS to $FOLDER_REPO \e[0m\n"
#echo -e "\n\e[94m[info] Current path $FOLDER_TESTS \e[0m\n"
#ls -alt "$FOLDER_TESTS/vendor/lucacracco"
#ls -alt "$FOLDER_REPO"

# Print versioning.
echo -e "\n\e[94m[info] Print versioning Tools \e[0m\n"
php -v
composer --version
./vendor/bin/robo --version
./vendor/bin/drush --version
./vendor/bin/drush status

echo -e "\n\e[94m[info] Use template settings \e[0m\n"
./vendor/bin/robo scaffold

echo -e "\n\e[94m[info] Install Drupal: Minimal profile \e[0m\n"
./vendor/bin/robo install minimal

echo -e "\n\e[94m[info] Export configurations \e[0m\n"
./vendor/bin/robo config:export

echo -e "\n\e[94m[info] Rebuild cache \e[0m\n"
./vendor/bin/robo cache-rebuild

echo -e "\n\e[94m[info] Install Drupal from configurations \e[0m\n"
./vendor/bin/robo install:config minimal

echo -e "\n\e[94m[info] Update configuration \e[0m\n"
./vendor/bin/drush config-set --no-interaction system.site name "Custom name"

echo -e "\n\e[94m[info] Deploy \e[0m\n"
./vendor/bin/robo deploy

echo -e "\n\e[94m[info] Export database in gzip \e[0m\n"
./vendor/bin/robo database:export /tmp --gzip
# todo: check file exist.
ls /tmp -alt

echo -e "\n\e[94m[info] Export database with custom filename \e[0m\n"
./vendor/bin/robo database:export /tmp --filename=drupal-dump

#echo -e "\n\e[94m[info] Install Drupal from database \e[0m\n"
#./vendor/bin/robo install:database /tmp/drupal-dump.sql
