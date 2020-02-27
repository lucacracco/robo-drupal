<?php

namespace LucaCracco\RoboDrupal\Task;

use Robo\Collection\Collection;
use Robo\Common\BuilderAwareTrait;
use Robo\Common\IO;
use Robo\Contract\BuilderAwareInterface;
use Robo\LoadAllTasks;
use Robo\Result;
use Robo\Task\BaseTask;

/**
 * Class BaseTasks.
 *
 * @package LucaCracco\RoboDrupal\Task
 */
abstract class BaseTasks extends BaseTask implements BuilderAwareInterface {

  use LoadAllTasks;
  //use BuilderAwareTrait;
  use IO;

  /**
   * {@inheritdoc}
   */
  public function run() {
    $this->printTaskInfo($this->getPrintedTaskName());
    $this->startTimer();
    $return = $this->getBuilder()->run();
    $this->stopTimer();
    return new Result($this, $return->getExitCode(), $return->getOutputData(), ['time' => $this->getExecutionTime()]);
  }

}
