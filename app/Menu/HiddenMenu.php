<?php

declare(strict_types=1);

namespace App\Menu;

use App\Service\Menu\Menu;
use App\Service\Menu\MenuInterface;

/**
 * Hidden menu.
 */
class HiddenMenu extends Menu implements MenuInterface {

  /**
   * System name of menu being managed.
   *
   * @var string
   */
  protected string $name = 'hidden';

  /**
   * Tree transformers.
   *
   * @var array
   */
  protected array $transformations = [
    ['callable' => 'menu.default_tree_manipulators:checkNodeAccess'],
    ['callable' => 'menu.default_tree_manipulators:checkAccess'],
    ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
  ];

}
