<?php

declare(strict_types=1);

namespace App\Service\Navigation;

use App\Service\Navigation\Link\LinkCollectionInterface;

/**
 * Builds navigations from menus.
 */
interface NavigationBuilderInterface {

  /**
   * Build a navigation.
   */
  public function build(
    string $menu_name,
    ?int $min_depth = 1,
    ?int $max_depth = 1,
    ?string $root = ''
  ): LinkCollectionInterface;

  /**
   * Get nids from a link collection as one-dimensional array.
   */
  public function getNids(LinkCollectionInterface $links): array;

}
