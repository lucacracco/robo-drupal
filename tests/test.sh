#!/bin/bash

# Exit when any command fails.
set -e

if ! command -v composer &>/dev/null; then
  echo "Composer executable could not be found"
  exit
fi

echo -e "\n\nDirectory running ${PWD}\n"
FOLDER_REPO=${FOLDER_REPO:="${PWD}"}
FOLDER_TESTS=${FOLDER_TESTS:="${PWD}/../robo-drupal-demo"}
DRUPAL_CORE_CONSTRAINT="${DRUPAL_CORE_CONSTRAINT:=~8.8.0}"

echo -e "\nClear/Create directory of install drupal for tests\n"
mkdir -p $FOLDER_TESTS
chmod 775 -R $FOLDER_TESTS
rm -Rf $FOLDER_TESTS

echo -e "\nInstalling Drupal $DRUPAL_CORE_CONSTRAINT on $FOLDER_TESTS\n"
composer create-project drupal/recommended-project:$DRUPAL_CORE_CONSTRAINT $FOLDER_TESTS

echo -e "\nInstalling Requirements\n"
composer require --no-interaction --working-dir=$FOLDER_TESTS \
  drupal/core-recommended:$DRUPAL_CORE_CONSTRAINT \
  drupal/core-dev:$DRUPAL_CORE_CONSTRAINT \
  drupal/core-composer-scaffold:$DRUPAL_CORE_CONSTRAINT \
  drupal/core:$DRUPAL_CORE_CONSTRAINT

echo -e "\nInstalling RoboDrupal: set custom repository for RoboDrupal\n"
composer config --working-dir=$FOLDER_TESTS repositories.1 "{\"type\": \"path\", \"url\": \"$FOLDER_REPO\", \"symlink\": true}"
composer require --no-interaction --working-dir=$FOLDER_TESTS lucacracco/robo-drupal:@dev

echo -e "\nUpdating dependencies\n"
composer update --no-interaction --working-dir=$FOLDER_TESTS

echo -e "\nCopy template settings\n"
cp -v "./tests/template/tpl.settings.php" "$FOLDER_TESTS/web/sites/default/tpl.settings.php"
cp -v "./tests/template/tpl.services.yml" "$FOLDER_TESTS/web/sites/default/tpl.services.yml"
cp -v "./tests/template/robo.yml" "$FOLDER_TESTS/robo.yml"

echo -e "\nOpen folder test: $FOLDER_TESTS\n"
cd "$FOLDER_TESTS"

# Debug
#echo -e "\nCheck symlink from $FOLDER_TESTS to $FOLDER_REPO\n"
#echo -e "\nCurrent path $FOLDER_TESTS\n"
#ls -alt "$FOLDER_TESTS/vendor/lucacracco"
#ls -alt "$FOLDER_REPO"

echo -e "\nScaffold and install Drupal minimal\n"
./vendor/bin/robo scaffold
./vendor/bin/robo install minimal

echo -e "\nScaffold and install Drupal minimal\n"
./vendor/bin/robo status

echo -e "\nRebuild cache\n"
./vendor/bin/robo cache-rebuild

echo -e "\nExport configurations\n"
./vendor/bin/robo config:export

echo -e "\nInstall Drupal from configurations\n"
./vendor/bin/robo install:config minimal

echo -e "\nUpdate configuration\n"
./vendor/bin/drush config-set --no-interaction system.site name "Custom name"

echo -e "\nDeploy\n"
./vendor/bin/robo deploy

#echo -e "\nExport database\n"
#./vendor/bin/robo database:export /tmp
#
#echo -e "\nInstall Drupal from database\n"
#./vendor/bin/robo install:database [dump]
