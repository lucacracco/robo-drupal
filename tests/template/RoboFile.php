<?php

use Consolidation\AnnotatedCommand\CommandData;

/**
 * This is an example.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks {

  use \LucaCracco\RoboDrupal\Task\Drupal\Tasks;

  /**
   * Example post command.
   *
   * @hook post-command install
   */
  public function postInstallCommand($result, CommandData $commandData) {
    $this->getBuilder()->addCode(function () {
      $this->say("Post-Command Hook");
    })->addTask($this->taskDrupal()->cacheRebuild())->run();
  }

}
