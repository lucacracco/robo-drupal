<?php

namespace LucaCracco\RoboDrupal\Task\Drupal;

use Drupal\Core\Site\Settings;
use DrupalFinder\DrupalFinder;
use LucaCracco\RoboDrupal\Task\BaseTasks;

/**
 * Class DrupalTask.
 *
 * @package LucaCracco\RoboDrupal\Task\Drupal
 */
class DrupalTask extends BaseTasks {

  use \LucaCracco\RoboDrupal\Task\Drush\Tasks;
  use \LucaCracco\RoboDrupal\Task\Twig\Tasks;

  /**
   * @var \DrupalFinder\DrupalFinder
   */
  protected $drupalFinder;

  /**
   * Site.
   *
   * @var string
   */
  protected $site = 'default';

  /**
   * DrupalTask constructor.
   */
  public function __construct() {
    parent::__construct();
    $this->drupalFinder = new DrupalFinder();
    // TODO: how define the locale root? for launch comand outsite root project.
    $this->drupalFinder->locateRoot('.');
  }

  /**
   * Rebuild a Drupal 8 site and clear all its caches.
   *
   * @return $this
   */
  public function cacheRebuild() {
    $task_list = [
      'cacheRebuild' => $this->getDrushStack()->drush('cache-rebuild'),
    ];
    $this->getBuilder()->addTaskList($task_list);
    return $this;
  }

  /**
   * Export configuration of drupal.
   *
   * @return $this
   */
  public function configExport() {
    $task_list = [
      'cacheRebuild' => $this->getDrushStack()->drush('cache-rebuild'),
      'configExport' => $this->getDrushStack()->drush('config-export'),
    ];
    $this->getBuilder()->addTaskList($task_list);
    return $this;
  }

  /**
   * Import configuration of drupal.
   *
   * @return $this
   */
  public function configImport() {
    $task_list = [
      'cacheRebuild' => $this->getDrushStack()->drush('cache-rebuild'),
      'configImport' => $this->getDrushStack()->drush('config-import'),
    ];
    $this->getBuilder()->addTaskList($task_list);
    return $this;
  }

  /**
   * Launch drupal core cron.
   *
   * @return $this
   */
  public function coreCron() {
    $task_list = [
      'coreCron' => $this->getDrushStack()->drush('core-cron'),
    ];
    $this->getBuilder()->addTaskList($task_list);
    return $this;
  }

  /**
   * Drop all tables in a Drupal database.
   *
   * @return $this
   */
  public function databaseDrop() {
    $task_list = [
      'sqlDrop' => $this->getDrushStack()->drush('sql-drop'),
    ];
    $this->getBuilder()->addTaskList($task_list);
    return $this;
  }

  /**
   * Export database.
   *
   * @param string $directory
   *   The directory to save the dump file.
   * @param string $file_name
   *   The filename used for dump generated.
   * @param boolean $gzip
   *   If the dump will be gzipped.
   *
   * @return $this
   */
  public function databaseExport($directory, $file_name = '', $gzip = FALSE) {
    if (!file_exists($directory)) {
      throw new \InvalidArgumentException("Path \"$directory\" where to save the dump is not found.");
    }
    $file_name = !empty($file_name) ? $file_name : date('Ymd_Hm');
    $path = $directory . DIRECTORY_SEPARATOR . $file_name . ".sql";

    $task_sql_dump = $this->getDrushStack()->drush('sql-dump')
      ->option('result-file', $path);

    if ($gzip) {
      $task_sql_dump->option('gzip');
    }

    $this->getBuilder()->addTaskList([
      'cacheRebuild' => $this->getDrushStack()->drush('cache-rebuild'),
      'sqlDump' => $task_sql_dump,
    ]);
    return $this;
  }

  /**
   * Import database.
   *
   * @param string $dump_file
   *
   * @return $this
   */
  public function databaseImport($dump_file) {
    $task_list = [
      'sqlCli' => $this->getDrushStack()
        ->drush('sql-cli < ')
        ->arg($dump_file),
    ];
    $this->getBuilder()->addTaskList($task_list);
    return $this;
  }

  /**
   * Deploy: run updb, config-import and clear cache.
   *
   * @return $this
   */
  public function deploy() {
    $task_list = [
      'updateDb' => $this->getDrushStack()->drush('updatedb'),
      'configImport' => $this->getDrushStack()->drush('config-import'),
      'cacheRebuild' => $this->getDrushStack()
        ->drush('cache-rebuild'),
      'configImport_2' => $this->getDrushStack()->drush('config-import'),
      'cacheRebuild_2' => $this->getDrushStack()->drush('cache-rebuild'),
    ];
    $this->getBuilder()->addTaskList($task_list);
    return $this;
  }

  /**
   * Return a drush stack with already set uri.
   *
   * @return \LucaCracco\RoboDrupal\Task\Drush\DrushTask
   */
  public function getDrushStack() {
    return $this->taskDrushStack($this->site);
  }

  /**
   * Get site selected.
   *
   * @return string
   */
  public function getSite() {
    return $this->site;
  }

  /**
   * Set site target.
   *
   * @param string $site
   *
   * @return $this
   */
  public function setSite(string $site) {
    $this->site = $site;
    return $this;
  }

  /**
   * Install Drupal profile.
   *
   * @param string $profile
   * @param string $username
   * @param string $password
   * @param string $mail
   * @param string $locale
   *
   * @return $this
   */
  public function install($profile = 'standard', $username = 'admin', $password = 'admin', $mail = "admin@example.com", $locale = 'en', $config = FALSE) {
    // Build task.
    $task = $this->getDrushStack()
      ->drush('site-install')
      ->arg($profile);

    if ($config) {
      $task->option('--existing-config');
    }

    //->rawArg("install_configure_form.update_status_module='array(FALSE,FALSE)'")
    //->rawArg("install_configure_form.enable_update_status_module=NULL")
    //->option('site-name', $this->getConfigValue('drupal.site.name'))
    //->option('site-mail', $this->getConfigValue('drupal.site.mail'))

    if ($username && $password && $mail) {
      $task->option('account-name', $username, '=')
        ->option('account-pass', $password, '=')
        ->option('account-mail', $mail);
    }
    if ($locale) {
      $task
        ->option('locale', $locale);
    }

    $task_list = [
      'install' => $task->printOutput(TRUE),
      'cacheRebuild' => $this->getDrushStack()->drush('cache-rebuild'),
    ];
    $this->getBuilder()->addTaskList($task_list);
    return $this;
  }

  /**
   * Checks for available translation updates.
   */
  public function localeCheck() {
    $task_list = [
      'localeCheck' => $this->getDrushStack()->drush('locale-check'),
    ];
    $this->getBuilder()->addTaskList($task_list);
    return $this;
  }

  /**
   * Imports the available translation updates.
   *
   * @return $this
   */
  public function localeUpdate() {
    $task_list = [
      'localeUpdate' => $this->getDrushStack()->drush('locale-update'),
    ];
    $this->getBuilder()->addTaskList($task_list);
    return $this;
  }

  /**
   * Active/disable maintenance_mode in Drupal site.
   *
   * @param bool $active
   *
   * @return $this
   */
  public function maintenanceMode($active = TRUE) {
    $task_list = [
      'systemMaintenanceMode' => $this->getDrushStack()
        ->drush('sset system.maintenance_mode')
        ->arg($active),
      'cacheRebuild' => $this->getDrushStack()->drush('cache-rebuild'),
    ];
    $this->getBuilder()->addTaskList($task_list);
    return $this;
  }

  /**
   * Scaffold file for Drupal.
   *
   * Create the settings.php and service.yml from default file template or twig
   * twig template.
   *
   * @return $this
   *
   * @throws \Robo\Exception\TaskException
   */
  public function scaffold() {
    $drupal_root = $this->drupalFinder->getDrupalRoot();
    $base = $drupal_root . "/sites/{$this->site}";

    // Create dir files if not exist.
    if (!file_exists($base . DIRECTORY_SEPARATOR . 'files')) {
      $this->getBuilder()->addTaskList([
        'createPublicFiles' => $this->taskFilesystemStack()
          ->mkdir($base . DIRECTORY_SEPARATOR . 'files'),
      ]);
    }

    // Copy or print the settings.php and services.yml files.
    $map = [
      'settings.php' => [
        //"{$this->enviroment}.tpl.settings.php",
        "tpl.settings.php",
        "default.settings.php",
      ],
      'services.yml' => [
        //"{$this->enviroment}.tpl.services.yml",
        "tpl.services.yml",
        "default.services.yml",
      ],
    ];

    foreach ($map as $destination_name => $sources) {
      foreach ($sources as $template_name) {

        $source = $base . DIRECTORY_SEPARATOR . $template_name;
        $destination = $base . DIRECTORY_SEPARATOR . $destination_name;
        if (!(file_exists($source) || file_exists($source . '.twig'))) {
          continue;
        }

        if (file_exists($destination)) {
          // Remove old file.
          $this->getBuilder()->addTaskList([
            "remove-" . $destination_name => $this->taskFilesystemStack()
              ->chmod(dirname($destination), 0775)
              ->chmod($destination, 0775)
              ->remove($destination),
          ]);
        }

        if (file_exists($source . '.twig')) {
          // Use twig template and engine to print new file.
          $this->getBuilder()->addTaskList([
            'renderTwig-' . $destination_name => $this->taskTwig()
              ->setTemplatesDirectory($base)
              ->setContext($this->getConfig()->export())
              ->applyTemplate(basename($source . '.twig'), $destination),
          ]);
          break;
        }

        // Copy file template.
        $this->getBuilder()->addTaskList([
          'copy-' . $destination_name => $this->taskFilesystemStack()
            ->copy($source, $destination),
        ]);
        break;
      }
    }

    // Generate hash_salt or other settings.
    require_once $drupal_root . '/core/includes/bootstrap.inc';
    require_once $drupal_root . '/core/includes/install.inc';
    new Settings([]);
    $settings['settings']['hash_salt'] = (object) [
      'value' => \Drupal\Component\Utility\Crypt::randomBytesBase64(55),
      'required' => TRUE,
    ];
    $this->getBuilder()->addCode(function () use ($settings, $base) {
      drupal_rewrite_settings($settings, $base . '/settings.php');
    });

    return $this;
  }

  /**
   * Display the status of site.
   *
   * @return $this
   */
  public function status() {
    $task_list = [
      'status' => $this->getDrushStack()->drush('status'),
    ];
    $this->getBuilder()->addTaskList($task_list);
    return $this;
  }

  /**
   * Apply any database updates required (as with running update.php).
   *
   * @return $this
   */
  public function updateDatabase() {
    $task_list = [
      'updatedb' => $this->getDrushStack()->drush('updatedb'),
    ];
    $this->getBuilder()->addTaskList($task_list);
    return $this;
  }

  /**
   * Display a one time login link for the given user account (defaults to uid
   * 1).
   *
   * @param string $user_name
   *
   * @return $this
   */
  public function userLogin($user_name) {
    $task_list = [
      'userLogin' => $this->getDrushStack()
        ->drush('user-login')
        ->option('name', $user_name)
        ->option('no-browser')
        ->printOutput(TRUE),
    ];
    $this->getBuilder()->addTaskList($task_list);
    return $this;
  }

}
