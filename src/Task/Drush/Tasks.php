<?php

namespace LucaCracco\RoboDrupal\Task\Drush;

/**
 * Trait Tasks for Drush.
 *
 * @package LucaCracco\RoboDrupal\Task\Drush
 */
trait Tasks {

  /**
   * Task drush.
   *
   * @param string|null $uri
   *   For multi-site installations, use a site alias or the --uri option to
   *   target a particular site.
   *
   * @return \LucaCracco\RoboDrupal\Task\Drush\DrushTask
   *   Drush task.
   */
  protected function taskDrushStack($uri = NULL) {

    /** @var \LucaCracco\RoboDrupal\Task\Drush\DrushTask $task */
    $task = $this->task(DrushTask::class);
    $task->setProcessInput($this->input());

    $output = $this->output();
    $task->setVerbosityThreshold($output->getVerbosity());

    if (!empty($uri)) {
      $task->uri($uri);
      return $task;
    }

    if ($this->input()->hasOption('site')) {
      $task->uri($this->input()->getOption('site'));
    }

    return $task;
  }

}
