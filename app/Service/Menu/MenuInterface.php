<?php

declare(strict_types=1);

namespace App\Service\Menu;

use App\Service\Menu\Link\LinkCollectionInterface;

/**
 * Provides an interface defining a menu service.
 */
interface MenuInterface {

  /**
   * Build the menu.
   */
  public function build(int $min_depth = 1, int $max_depth = 1, string $root = ''): LinkCollectionInterface;

  /**
   * Get nids from a link collection as one-dimensional array.
   */
  public function getNids(LinkCollectionInterface $links): array;

}
