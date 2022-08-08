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
   * Check run compatibility with taskDrupal8..
   *
   * @hook post-command install
   */
  public function postInstallCommand($result, CommandData $commandData) {
    $this->getBuilder()->addCode(function () {
      $this->say("Post-Command Hook");
    })->addTask($this->taskDrupal8()->cacheRebuild())->run();
  }

}
