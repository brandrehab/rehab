<?php

declare(strict_types=1);

namespace App\Menu;

use App\Service\Menu\Menu;
use App\Service\Menu\MenuInterface;

/**
 * Admin toolbar menu.
 */
class AdminToolbarMenu extends Menu implements MenuInterface {

  /**
   * System name of menu being managed.
   *
   * @var string
   */
  protected $name = 'admin-toolbar';

  /**
   * Tree transformers.
   *
   * @var array
   */
  protected $transformations = [
    ['callable' => 'menu.default_tree_manipulators:checkNodeAccess'],
    ['callable' => 'menu.default_tree_manipulators:checkAccess'],
    ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
  ];

}
