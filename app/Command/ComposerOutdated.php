<?php

declare(strict_types=1);

namespace App\Command;

use Drush\Commands\DrushCommands;
use Symfony\Component\Process\Process;

/**
 * Composer outdated command.
 */
class ComposerOutdated extends DrushCommands {

  /**
   * The composer process.
   *
   * @var \Symfony\Component\Process\Process
   */
  private Process $composer;

  /**
   * The core process.
   *
   * @var \Symfony\Component\Process\Process
   */
  private Process $core;

  /**
   * The db process.
   *
   * @var \Symfony\Component\Process\Process
   */
  private Process $db;

  /**
   * The cache process.
   *
   * @var \Symfony\Component\Process\Process
   */
  private Process $cache;

  /**
   * Check whether drupal core or any contrib modules are out of date.
   *
   * @command composer:outdated
   * @aliases co
   * @usage drush composer:outdated
   *   Check whether any composer updates are available.
   */
  public function outdated(): int {
    $this->io()->title('Checking for available updates');

    if (!$this->startComposer()) {
      $this->io()->error('Unable to start composer. Aborting.');
      return self::EXIT_FAILURE;
    }

    $composer_output = trim($this->composer->getOutput());
    $this->io()->listing(explode("\n", $composer_output));

    if (strpos($composer_output, 'drupal/core') === FALSE) {
      return self::EXIT_SUCCESS;
    }

    if (!$this->io()->confirm('An update for drupal/core is available. Do you wish to update now?', FALSE)) {
      return self::EXIT_SUCCESS;
    }

    if (!$this->updateCore()) {
      $this->io()->error('Failed to update core. Aborting.');
      return self::EXIT_FAILURE;
    }

    if (!$this->updateDb()) {
      $this->io()->error('Failed to update the database. Aborting.');
      return self::EXIT_FAILURE;
    }

    if (!$this->clearCache()) {
      $this->io()->error('Failed to clear the cache. Aborting.');
      return self::EXIT_FAILURE;
    }

    $this->io()->newLine();

    return self::EXIT_SUCCESS;
  }

  /**
   * Clear the cache.
   */
  private function clearCache(): bool {
    $this->io()->section('Clearing the cache');
    $this->cache = $this->processManager()->process([
      './drush',
      'cr',
    ], '../vendor/bin');

    $this->cache->run(function ($type, $buffer) {
      $this->io->text($buffer);
    });

    return $this->cache->isSuccessful();
  }

  /**
   * Update the database.
   */
  private function updateDb(): bool {
    $this->io()->section('Updating the database');
    $this->db = $this->processManager()->process([
      './drush',
      'updatedb',
    ], '../vendor/bin');

    $this->db->run(function ($type, $buffer) {
      $this->io->text($buffer);
    });

    return $this->db->isSuccessful();
  }

  /**
   * Update drupal/core and any dependencies.
   */
  private function updateCore(): bool {
    $this->io()->section('Updating drupal/core');
    $this->core = $this->processManager()->process([
      'composer',
      'update',
      'drupal/core-recommended',
      'drupal/core-composer-scaffold',
      'drupal/core-project-message',
      '--with-dependencies',
    ], '..');

    $this->core->run(function ($type, $buffer) {
      $this->io->write($buffer);
    });

    return $this->core->isSuccessful();
  }

  /**
   * Run composer outdated against drupal repos.
   */
  private function startComposer(): bool {
    $this->composer = $this->processManager()->process([
      'composer',
      'outdated',
      'drupal/*',
    ], '..');
    $this->composer->run();
    return $this->composer->isSuccessful();
  }

}
