<?php

namespace LucaCracco\RoboDrupal\Task\Drupal;

/**
 * Trait Tasks for Drupal.
 *
 * @package LucaCracco\RoboDrupal\Task\Drupal
 */
trait Tasks {

  /**
   * Task Drupal8.
   *
   * @return \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task
   *   Drupal task.
   */
  protected function taskDrupal8() {

    /** @var \LucaCracco\RoboDrupal\Task\Drupal\Drupal8Task $task */
    $task = $this->task(Drupal8Task::class);
    $task->setInput($this->input());

    /** @var \Symfony\Component\Console\Output\OutputInterface $output */
    $output = $this->output();
    $task->setVerbosityThreshold($output->getVerbosity());

    if (method_exists($this, 'getSite')) {
      $task->setSite($this->getSite());
    }

    return $task;
  }

}
