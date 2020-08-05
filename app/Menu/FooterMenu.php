<?php

declare(strict_types=1);

namespace App\Menu;

use App\Service\Menu\Menu;
use App\Service\Menu\MenuInterface;

/**
 * Footer menu.
 */
class FooterMenu extends Menu implements MenuInterface {

  /**
   * System name of menu being managed.
   *
   * @var string
   */
  protected $name = 'footer';

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
