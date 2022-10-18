<?php

namespace LucaCracco\RoboDrupal\Task\Twig;

/**
 * Trait Tasks for Twig.
 *
 * @package LucaCracco\RoboDrupal\Task\Twig
 */
trait Tasks {

  /**
   * Task twig.
   *
   * @return \LucaCracco\RoboDrupal\Task\Twig\TwigTask
   *   Drush task.
   */
  protected function taskTwig() {

    /** @var \LucaCracco\RoboDrupal\Task\Twig\TwigTask $task */
    $task = $this->task(TwigTask::class);

    $output = $this->output();
    $task->setVerbosityThreshold($output->getVerbosity());

    return $task;
  }

}
