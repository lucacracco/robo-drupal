<?php

namespace LucaCracco\RoboDrupal\Traits;

use Consolidation\Config\Loader\ConfigProcessor;
use Consolidation\Config\Loader\YamlConfigLoader;
use Robo\Robo;

/**
 * Trait ConfigLoader.
 *
 * TODO: implement. Not work.
 *
 * @package LucaCracco\RoboDrupal\Traits
 */
trait ConfigLoader {

  /**
   * The name reference used to load the configurations.
   *
   * @var string
   */
  protected $baseConfigName;

  /**
   * Load configurations from file or other storage.
   */
  protected function loadConfig() {
    // Initialize configuration objects.
    $config = Robo::config();
    $loader = new YamlConfigLoader();
    $processor = new ConfigProcessor();

    // Extend and import configuration.
    $processor->add($config->export());
    $processor->extend($loader->load($this->baseConfigName . '.robo.yml'));
    // $processor->extend($loader->load($input->getOption('config')));

    // Export configurations loaded with unprocessed tokens.
    $export = $processor->export();
    $expanded = \Grasmash\YamlExpander\Expander::expandArrayProperties($export);

    // Reimport the config, with the tokens replaced.
    $config->replace($expanded);
  }

}