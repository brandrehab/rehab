<?php

declare(strict_types=1);

namespace App\Entity;

use Drupal\node\Entity\Node;

/**
 * Page entity.
 */
class Page extends Node implements PageInterface {

  /**
   * Get the node type name.
   */
  public static function getStaticBundle() {
    return 'page';
  }

}
