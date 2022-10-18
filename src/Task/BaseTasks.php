<?php

namespace LucaCracco\RoboDrupal\Task;

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
  use IO;

  /**
   * BaseTasks constructor.
   */
  public function __construct() {
    // Nothing.
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    $this->printTaskInfo($this->getPrintedTaskName());
    $this->startTimer();
    $return = $this->collectionBuilder()->run();
    $this->stopTimer();
    return new Result($this, $return->getExitCode(), $return->getOutputData(), ['time' => $this->getExecutionTime()]);
  }

}
