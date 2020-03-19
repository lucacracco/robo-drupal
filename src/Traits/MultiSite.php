<?php

namespace LucaCracco\RoboDrupal\Traits;

use Consolidation\AnnotatedCommand\AnnotationData;
use DrupalFinder\DrupalFinder;
use \Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Consolidation\Config\Loader\ConfigProcessor;
use Consolidation\Config\Loader\YamlConfigLoader;
use Robo\Robo;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Trait MultiSite.
 *
 * @package LucaCracco\RoboDrupal\Traits
 */
trait MultiSite {

  /**
   * Site.
   *
   * @var string
   */
  protected $site;

  /**
   * Get site.
   *
   * @return string
   */
  public function getSite(): string {
    return $this->site;
  }

  /**
   * Return an array with alias of site are available in this installation.
   *
   * TODO: implement.
   *
   * @return array
   */
  public function getSitesAvailable() {
    $drupalFinder = new DrupalFinder();
    $drupalFinder->locateRoot('.');
    $base = $drupalFinder->getDrupalRoot() . "/sites/sites.php";

    if (!file_exists($base)) {
      return ['default' => 'default'];
    }

    // Path to sites.php file.
    include($base);
    $sites = array_flip($sites);
    return $sites;
  }

  /**
   * @hook interact
   */
  public function interact(InputInterface $input, OutputInterface $output, AnnotationData $annotationData) {
    $sites = $this->getSitesAvailable();
    $io = new SymfonyStyle($input, $output);
    $site = $input->getOption('site');
    if (empty($site) || !in_array($site, array_keys($sites))) {
      $site = $io->choice("Enter a site:", $sites);
      $input->setOption('site', $site);
    }

    $this->setSite($site);

    //// Initialize configuration objects.
    //$config = Robo::config();
    //$loader = new YamlConfigLoader();
    //$processor = new ConfigProcessor();
    //
    //// Extend and import configuration.
    //$processor->add($config->export());
    //$processor->extend($loader->load($site . '.robo.yml'));
    ////    $processor->extend($loader->load($input->getOption('config')));
    //
    //// Export configurations loaded with unprocessed tokens.
    //$export = $processor->export();
    //$expanded = \Grasmash\YamlExpander\Expander::expandArrayProperties($export);
    //
    //// Reimport the config, with the tokens replaced.
    //$config->replace($expanded);
  }

  /**
   * Set site.
   *
   * @param string $site
   *
   * @return $this
   */
  public function setSite(string $site) {
    $this->site = $site;
    return $this;
  }

  /**
   * @hook option
   */
  public function siteOptions(Command $command, AnnotationData $annotationData) {
    $command->addOption('site', 's', InputOption::VALUE_OPTIONAL, 'Indicate which site you are running the command.', 'default');
  }

}
