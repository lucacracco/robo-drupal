# Robo.li & Drupal8

This repository contains a library dedicated to the initialization, building,
management and maintenance of Drupal8 projects through the assistance of
Robo.li.

## Install

```bash
composer require lucacracco/robo-drupal:^1.0
```

## Use

Use `vendor/bin/robo list` for see all commands.

### Scaffolding and install Drupal example

Require library

    composer require lucacracco/robo-drupal:^1.0

Copy template files for settings/services

      cp -v "[root]/vendor/lucacracco/robo-drupal/tests/template/tpl.settings.php" "[root]/web/sites/default/tpl.settings.php"
      cp -v "[root]/vendor/lucacracco/robo-drupal/tests/template/tpl.services.yml" "[root]/web/sites/default/tpl.services.yml"

Run command for scaffold settings/services

    ./vendor/bin/robo scaffold

Run command for install Drupal (standard profile)

    ./vendor/bin/robo install standard

## Notes

* **Recommended for Drupal ~8.8.0**;
* The `1.x` releases require `consolidation/robo:~1`;
* When use multisite features is required to configure `sites.php` with all
  sites that you are working.
 