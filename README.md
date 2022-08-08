# Robo.li & Drupal

This repository contains a library dedicated to the initialization, building,
management and maintenance of Drupal8/9 projects through the assistance of
Robo.li.

## Install

```bash
composer require lucacracco/robo-drupal
```

## Use

Use `vendor/bin/robo list` for see all commands.

### Scaffolding and install Drupal example

Require library

    composer require lucacracco/robo-drupal

Copy template files for settings/services

      cp -v "[root]/vendor/lucacracco/robo-drupal/tests/template/tpl.settings.php" "[root]/web/sites/default/tpl.settings.php"
      cp -v "[root]/vendor/lucacracco/robo-drupal/tests/template/tpl.services.yml" "[root]/web/sites/default/tpl.services.yml"

Run command for scaffold settings/services

    ./vendor/bin/robo scaffold

Run command for install Drupal (standard profile)

    ./vendor/bin/robo install standard

## Notes

* **Recommended for Drupal ~8.9.0**;
* The `2.x` releases require `consolidation/robo:~2`;
* When use multisite features is required to configure `sites.php` with all
  sites that you are working.
 