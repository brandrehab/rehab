<?php

declare(strict_types=1);

namespace App\Repository;

use App\Service\Menu\MenuInterface;

/**
 * Menu Repository interface.
 */
interface MenuRepositoryInterface {

  /**
   * Attempt to get a menu by name.
   */
  public function get($menu_name): ?MenuInterface;

}
