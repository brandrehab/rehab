<?php

declare(strict_types=1);

namespace App\Menu;

use App\Service\Menu\Menu;

/**
 * Main menu.
 */
class MainMenu extends Menu {

  /**
   * System name of menu being managed.
   *
   * @var string
   */
  protected $name = 'main';

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
