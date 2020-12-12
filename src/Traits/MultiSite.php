<?php

namespace LucaCracco\RoboDrupal\Traits;

use Consolidation\AnnotatedCommand\AnnotationData;
use Consolidation\AnnotatedCommand\CommandData;
use DrupalFinder\DrupalFinder;
use \Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
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
  public function getSite() {
    return $this->site;
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
   * Request what site the command is applied.
   *
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
  }

  /**
   * @hook option
   */
  public function siteOptions(Command $command, AnnotationData $annotationData) {
    $command->addOption('site', 's', InputOption::VALUE_OPTIONAL, 'Indicate which site you are running the command.', 'default');
  }

  /**
   * Validate site selected and set variable of trait.
   *
   * @hook validate
   */
  public function validateSite(CommandData $commandData) {

    // Retrieve the site option selected.
    // Selected in @hook interact or pass how option in the command.
    $input = $commandData->input();
    $site = $input->getOption('site');
    $sites = $this->getSitesAvailable();

    if (empty($site)) {
      // The fallback value from getSitesAvailable is 'default', when sites.php
      // is empty or not found.
      $first_site_declared = reset($sites);
      $this->setSite(key($first_site_declared));
      return;
    }

    if (!in_array($site, array_keys($sites))) {
      throw new \InvalidArgumentException("Site $site selected not found");
    }

    // Set site.
    $this->setSite($site);
  }

}
