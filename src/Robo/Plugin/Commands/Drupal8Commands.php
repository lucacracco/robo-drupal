<?php

namespace LucaCracco\RoboDrupal\Robo\Plugin\Commands;

/**
 * Class DrupalCommands.
 *
 * @package LucaCracco\RoboDrupal\Robo\Plugin\Commands
 */
class Drupal8Commands extends \Robo\Tasks {

  use \LucaCracco\RoboDrupal\Task\Drupal\Tasks;
  use \LucaCracco\RoboDrupal\Traits\MultiSite;

  /**
   * Drupal rebuild cache.
   *
   * Rebuild a Drupal 8 site and clear all its caches.
   *
   * @command cache-rebuild
   *
   * @aliases dcr
   * @usage dcr
   */
  public function cacheRebuild() {
    return $this->taskDrupal8()->cacheRebuild();
  }

  /**
   * Export configuration of drupal.
   *
   * @command config:export
   *
   * @arg config_export Destination directory_sync to save the configurations.
   *
   * @aliases dce
   * @usage dce
   */
  public function configExport($config_export = 'sync') {
    return $this->taskDrupal8()->configExport($config_export);
  }

  /**
   * Import configuration of drupal.
   *
   * @command config:import
   *
   * @aliases dci
   * @usage dci
   */
  public function configImport() {
    return $this->taskDrupal8()->configImport();
  }

  /**
   * Launch drupal core cron.
   *
   * @command core-cron
   *
   * @aliases dcron
   */
  public function coreCron() {
    return $this->taskDrupal8()->coreCron();
  }

  /**
   * Drop all tables in a Drupal database.
   *
   * @command database:drop
   *
   * @interactConfirmCommand
   */
  public function databaseDrop() {
    return $this->taskDrupal8()->databaseDrop();
  }

  /**
   * Export database.
   *
   * @option directory Where to save the dump file.
   *
   * @command database:export
   */
  public function databaseExport($directory) {
    if (!file_exists($directory)) {
      // TODO: crete directory.
      throw new \InvalidArgumentException("Path \"$directory\" where to save the dump is not found.");
    }
    return $this->taskDrupal8()->databaseExport($directory);
  }

  /**
   * Import database.
   *
   * @command database:import
   *
   * @arg dump_file Path of dump file to import.
   */
  public function databaseImport($dump_file) {
    // TODO: check if dump file exist.
    return $this->taskDrupal8()->databaseImport($dump_file);
  }

  /**
   * Install Drupal profile.
   *
   * @param $profile
   * @param array $opt
   *
   * @command install
   *
   * @aliases dsi
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
   */
  public function install($profile, $opt = [
    'username' => 'admin',
    'password' => 'admin',
    'mail' => 'admin@example.com',
    'locale' => 'en',
  ]) {
    return $this->taskDrupal8()
      ->install($profile, $opt['username'], $opt['password'], $opt['mail'], $opt['locale']);
  }

  /**
   * Install Drupal profile with config.
   *
   * @param $profile
   * @param array $opt
   *
   * @command install:config
   *
   * @aliases dsi
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
   */
  public function installConfig($profile, $opt = [
    'username' => 'admin',
    'password' => 'admin',
    'mail' => 'admin@example.com',
    'locale' => 'en',
  ]) {
    return $this->taskDrupal8()
      ->install($profile, $opt['username'], $opt['password'], $opt['mail'], $opt['locale'], TRUE);
  }

  /**
   * Checks for available translation updates.
   *
   * @command locale:check
   */
  public function localeCheck() {
    return $this->taskDrupal8()->localeCheck();
  }

  /**
   * Imports the available translation updates.
   *
   * @command locale:update
   */
  public function localeUpdate() {
    return $this->taskDrupal8()->localeUpdate();
  }

  /**
   * Active/disable maintenance_mode in Drupal site.
   *
   * @command maintenance-mode
   *
   * @arg active Indicate the status of maintenance mode to set.
   *
   * @aliases dmm
   */
  public function maintenanceMode($active = TRUE) {
    return $this->taskDrupal8()->maintenanceMode($active);
  }

  /**
   * Scaffold the files for Drupal.
   *
   * Create the settings.php/services.yml from default template.
   *
   * @command scaffold
   */
  public function scaffold() {
    return $this->taskDrupal8()->scaffold();
  }

  /**
   * Print status of site.
   *
   * @command status
   */
  public function status() {
    print_r($this->input()->getOptions());
    return $this->taskDrupal8()->status()->run();
  }

  /**
   * Apply any database updates required (as with running update.php).
   *
   * @command updatedb
   *
   * @aliases dupdb
   */
  public function updateDatabase() {
    return $this->taskDrupal8()->updateDatabase();
  }

  /**
   * Display a one time login link for the given user account (defaults to uid 1).
   *
   * @command user-login
   *
   * @arg user An optional uid, user name, or email address for the user to log in as. Default is to log in as uid 1.
   *
   * @aliases duli
   */
  public function userLogin($user) {
    return $this->taskDrupal8()->userLogin($user);
  }

}
