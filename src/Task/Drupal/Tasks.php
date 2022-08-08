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

    /** @var \Symfony\Component\Console\Output\OutputInterface $output */
    $output = $this->output();
    $task->setVerbosityThreshold($output->getVerbosity());

    if (method_exists($this, 'getSite') && $this->getSite()) {
      $task->setSite($this->getSite());
    }

    return $task;
  }

  /**
   * Task Drupal8.
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\DrupalTask
   *   Drupal task.
   * @deprecated in 2.x and is removed from 3.x. Use ::taskDrupal() instead
   */
  protected function taskDrupal8() {
    @trigger_error('\LucaCracco\RoboDrupal\Task\Drupal\Tasks::taskDrupal8() is deprecated in 2.x and is removed from 3.x. Use \LucaCracco\RoboDrupal\Task\Drupal\Tasks::taskDrupal() instead.', E_USER_DEPRECATED);
    return $this->taskDrupal();
  }

}
