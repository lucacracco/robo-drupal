<?php

namespace LucaCracco\RoboDrupal\Task\Drupal;

/**
 * Trait Tasks for Drupal.
 *
 * @package LucaCracco\RoboDrupal\Task\Drupal
 */
trait Tasks {

  /**
   * Task Drupal.
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\DrupalTask
   *   Drupal task.
   */
  protected function taskDrupal() {

    /** @var \LucaCracco\RoboDrupal\Task\Drupal\DrupalTask $task */
    $task = $this->task(DrupalTask::class);
    $task->setInput($this->input());

    $output = $this->output();
    $task->setVerbosityThreshold($output->getVerbosity());

    if (method_exists($this, 'getSite') && $this->getSite()) {
      $task->setSite($this->getSite());
    }

    return $task;
  }

}
