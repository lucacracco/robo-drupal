<?php

namespace LucaCracco\RoboDrupal\Task\Twig;

use Robo\Common\TaskIO;
use Robo\Exception\TaskException;
use Robo\Exception\TaskExitException;
use Robo\Task\BaseTask;
use Twig_Environment;
use Twig_Extension;
use Twig_Loader_Array;
use Twig_Loader_Filesystem;

/**
 * Class TwigTask.
 *
 * @see https://github.com/gheydon/robo-twig/blob/2.x/src/Twig.php
 *
 * @package LucaCracco\RoboDrupal\Task
 */
class TwigTask extends BaseTask {

  use TaskIO;

  protected $context = [];

  protected $extensions = [];

  protected $processes = [];

  protected $templatesArray = [];

  protected $templatesDirectory;

  /**
   * @param Twig_Extension $extensions
   *
   * @return $this
   */
  public function addExtension(Twig_Extension $extension) {
    $this->extensions[] = $extension;

    return $this;
  }

  /**
   * @param $template
   * @param $destination
   * @param array $variables
   *
   * @return $this
   */
  public function applyTemplate($template, $destination, array $variables = []) {
    $this->processes[] = [
      'template' => $template,
      'destination' => $destination,
      'variables' => $variables,
    ];

    return $this;
  }

  /**
   * @return \Robo\Result|void
   * @throws \Robo\Exception\TaskExitException
   */
  public function run() {
    if (!isset($this->templatesDirectory) && empty($this->templatesArray)) {
      throw new TaskExitException($this, 'Templates have not been defined.');
    }
    if (isset($this->templatesDirectory)) {
      $loader = new Twig_Loader_Filesystem($this->templatesDirectory);
    }
    elseif (!empty($this->templatesArray)) {
      $loader = new Twig_Loader_Array($this->templatesArray);
    }

    $twig = new Twig_Environment($loader);

    if (!empty($this->extensions)) {
      foreach ($this->extensions as $extension) {
        $twig->addExtension($extension);
      }
    }

    foreach ($this->processes as $process) {
      if (!empty($process['destination'])) {
        $destination = $process['destination'];
        if (is_dir($destination)) {
          $destination .= '/' . $process['template'];

          if (substr($destination, -5) == '.twig') {
            $destination = substr($destination, 0, -5);
          }
        }
        file_put_contents($destination, $twig->render($process['template'], $process['variables'] + $this->context));
        $this->printTaskInfo('Writing template "' . $process['template'] . '" to file "' . $destination . '"');
      }
      else {
        $this->printTaskInfo($twig->render($process['template'], $process['variables'] + $this->context));
      }
    }
  }

  /**
   * @param $id
   * @param null $value
   *
   * @return $this
   */
  public function setContext($id, $value = NULL) {
    if (is_array($id)) {
      $this->context = $id;
      return $this;
    }

    $this->context[$id] = $value;

    return $this;
  }

  /**
   * @param mixed $templatesArray
   *
   * @return $this
   * @throws \Robo\Exception\TaskException
   */
  public function setTemplatesArray($id, $content = NULL) {
    if (isset($this->templatesDirectory)) {
      throw new TaskException($this, 'template directory is already in use, unable to combine with template array.');
    }

    // reset the template array with the new variables.
    if (is_array($id)) {
      $this->templatesArray = $id;
      return $this;
    }
    $this->templatesArray[$id] = $content;

    return $this;
  }

  /**
   * @param string $templates_dir
   *
   * @return $this
   * @throws \Robo\Exception\TaskException
   */
  public function setTemplatesDirectory($templates_dir) {
    if (!empty($this->templatesArray)) {
      throw new TaskException($this, 'template array is already in use, unable to combine with template directory.');
    }
    $this->templatesDirectory = $templates_dir;
  }

}