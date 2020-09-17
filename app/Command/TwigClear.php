<?php

declare(strict_types=1);

namespace App\Command;

use Drush\Commands\DrushCommands;
use Drupal\Core\PhpStorage\PhpStorageFactory;
use Drupal\Core\Template\TwigEnvironment;
use Drupal\Core\Cache\Cache;

/**
 * Twig clear command.
 */
class TwigClear extends DrushCommands {

  /**
   * Twig environment.
   *
   * @var \Drupal\Core\Template\TwigEnvironment
   */
  private $twig;

  /**
   * Class constructor.
   */
  public function __construct(TwigEnvironment $twig) {
    $this->twig = $twig;
  }

  /**
   * Check whether drupal core or any contrib modules are out of date.
   *
   * @command twig:clear
   * @aliases tc
   * @usage drush twig:clear
   *   Clears cached twig templates without running any other caches.
   */
  public function clear(): int {
    $this->io()->title('Clearing twig cache.');

    $this->twig->invalidate();
    PhpStorageFactory::get('twig')->deleteAll();
    Cache::invalidateTags(['rendered']);

    $this->io()->success('Twig cache cleared.');

    return self::EXIT_SUCCESS;
  }

}
