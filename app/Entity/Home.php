<?php

declare(strict_types=1);

namespace App\Entity;

use Drupal\node\Entity\Node;

/**
 * Home entity.
 */
class Home extends Node implements HomeInterface {

  /**
   * Get the node type name.
   */
  public static function getStaticBundle() {
    return 'home';
  }

}
