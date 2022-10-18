<?php

/**
 * This is an example.
 *
 * @see http://robo.li/
 */
class RoboFileExample extends \Robo\Tasks {

  use \Robo\Common\IO;
  use \Robo\Task\Filesystem\Tasks;
  use \LucaCracco\RoboDrupal\Task\Drush\Tasks;
  use \LucaCracco\RoboDrupal\Traits\MultiSite;

  /**
   * @hook process drupal:scaffold
   */
  public function process($result, \Consolidation\AnnotatedCommand\CommandData $commandData) {
    if ($result instanceof \Robo\Collection\CollectionBuilder) {
      $result->getCollection()
        ->after('copy-settings.php', $this->taskFilesystemStack()
          ->touch("pippo.txt")
          ->remove('pippo.txt'));
    }
  }

  /**
   * Replace status command.
   *
   * @hook replace-command test:simple
   */
  public function status() {
    $this->yell("Selected \"{$this->getSite()}\" (replace command)");
  }

  /**
   * Print site selected.
   *
   * @command test:simple
   */
  public function testSimple() {
    $this->yell("Selected \"{$this->getSite()}\"");
  }

}
