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
   * @aliases dcr
   * @usage dcr
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
   */
  public function cacheRebuild() {
    return $this->taskDrupal8()->cacheRebuild();
  }

  /**
   * Export configuration of drupal.
   *
   * @command config:export
   * @aliases dce
   * @usage dce
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
   */
  public function configExport() {
    return $this->taskDrupal8()->configExport();
  }

  /**
   * Import configuration of drupal.
   *
   * @command config:import
   * @aliases dci
   * @usage dci
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
   */
  public function configImport() {
    return $this->taskDrupal8()->configImport();
  }

  /**
   * Launch drupal core cron.
   *
   * @command core-cron
   * @aliases dcron
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
   */
  public function coreCron() {
    return $this->taskDrupal8()->coreCron();
  }

  /**
   * Drop all tables in a Drupal database.
   *
   * @command database:drop
   * @interactConfirmCommand
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
   */
  public function databaseDrop() {
    return $this->taskDrupal8()->databaseDrop();
  }

  /**
   * Export database.
   *
   * @option directory Where to save the dump file.
   * @command database:export
   *
   * @param string $directory
   *   The path of directory where save the dump file.
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
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
   * @arg dump_file Path of dump file to import.
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
   */
  public function databaseImport($dump_file) {
    // TODO: check if dump file exist.
    return $this->taskDrupal8()->databaseImport($dump_file);
  }

  /**
   * Deploy: run updb, config-import and clear cache.
   *
   * @command deploy
   * @aliases dpl
   */
  public function deploy() {
    return $this->taskDrupal8()->deploy();
  }

  /**
   * Install Drupal profile.
   *
   * @command install
   * @arg profile Profile name to use.
   * @aliases dsi
   *
   * @param string $profile
   *   The profile name to use.
   * @param array $opt
   *   An array of options for installation.
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
   * @command install:config
   * @arg profile Profile name to use.
   * @aliases dsic
   *
   * @param string $profile
   *   The profile name to use.
   * @param array $opt
   *   An array of options for installation.
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
   * Install database (drop exist and import the dump target). Only .sql file.
   *
   * @command install:database
   * @aliases dsid
   *
   * @param string $dump_file
   *   The path of dump file to use.
   *
   * @return \Robo\Collection\CollectionBuilder
   */
  public function installDatabase($dump_file) {
    $task_list = [];
    $file = new \SplFileInfo($dump_file);
    $ext = $file->getExtension();

    if (!file_exists($file) || $ext != 'sql') {
      throw new \InvalidArgumentException("The file does not respect the correct format.");
    }

    $task_list['sqlDrop'] = $this->taskDrupal8()
      ->getDrushStack()
      ->drush('sql-drop');
    $task_list['sqlCli'] = $this->taskDrupal8()->getDrushStack()
      ->drush('sql-cli < ')
      ->arg($dump_file);
    $this->getBuilder()->addTaskList($task_list);
    return $this->getBuilder();
  }

  /**
   * Checks for available translation updates.
   *
   * @command locale:check
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
   */
  public function localeCheck() {
    return $this->taskDrupal8()->localeCheck();
  }

  /**
   * Imports the available translation updates.
   *
   * @command locale:update
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
   */
  public function localeUpdate() {
    return $this->taskDrupal8()->localeUpdate();
  }

  /**
   * Active/disable maintenance_mode in Drupal site.
   *
   * @command maintenance-mode
   * @arg active Indicate the status of maintenance mode to set.
   * @aliases dmm
   *
   * @param bool $active
   *   Status to be set.
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
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
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
   * @throws \Robo\Exception\TaskException
   */
  public function scaffold() {
    return $this->taskDrupal8()->scaffold();
  }

  /**
   * Print status of site.
   *
   * @command status
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
   */
  public function status() {
    return $this->taskDrupal8()->status();
  }

  /**
   * Apply any database updates required (as with running update.php).
   *
   * @command updatedb
   * @aliases dupdb
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
   */
  public function updateDatabase() {
    return $this->taskDrupal8()->updateDatabase();
  }

  /**
   * Display a one time login link for the given user account (defaults to uid 1).
   *
   * @command user-login
   * @arg user An optional uid, user name, or email address for the user to log in as. Default is to log in as uid 1.
   * @aliases duli
   *
   * @param string $user
   *    The user name id, name or email address.
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
   */
  public function userLogin($user) {
    return $this->taskDrupal8()->userLogin($user);
  }

}
