<?php

declare(strict_types=1);

namespace App\Command;

use Drush\Commands\DrushCommands;

/**
 * Check the code against Drupal Standards.
 */
class DrupalStandardsCompliant extends DrushCommands {

  /**
   * Location of phpstan.
   *
   * @var string
   */
  private const PHPSTAN_BINARY = __DIR__ . '/../../../vendor/phpstan/phpstan/phpstan';

  /**
   * The phpstan process.
   *
   * @var \Symfony\Component\Process\Process
   */
  private $phpstan;

  /**
   * Check whether the code is Drupal Standards Compliant.
   *
   * @command standards:compliant
   * @aliases dsc
   * @usage drush standards:compliant
   *   Check whether the code is Drupal Standards Compliant.
   */
  public function check(): int {
    $this->io()->title('Checking the code cuts the mustard');

    if (!file_exists(self::PHPSTAN_BINARY)) {
      $this->io()->error('Unable to locate phpstan.');
      return self::EXIT_FAILURE;
    };

    $this->phpstan = $this->processManager()->process([
      self::PHPSTAN_BINARY,
      'analyse',
      '-c',
      '../phpstan.neon',
      '--error-format=table',
      __DIR__ . '/../../app/',
      __DIR__ . '/../../modules/custom/',
      __DIR__ . '/../../themes/custom/',
    ]);

    $this->phpstan->run(function ($type, $buffer) {
      $this->io->write($buffer);
    });

    if (!$this->phpstan->isSuccessful()) {
      $this->io()->error('Phpstan failed.');
      return self::EXIT_FAILURE;
    }

    return self::EXIT_SUCCESS;
  }

}
