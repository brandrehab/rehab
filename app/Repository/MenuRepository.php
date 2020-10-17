<?php

declare(strict_types=1);

namespace App\Repository;

use App\Service\Menu\MenuInterface;

/**
 * Menu Repository class.
 */
class MenuRepository implements MenuRepositoryInterface {


  /**
   * Main menu.
   *
   * @var \App\Service\Menu\MenuInterface
   */
  protected $main;

  /**
   * Footer menu.
   *
   * @var \App\Service\Menu\MenuInterface
   */
  protected $footer;

  /**
   * Hidden menu.
   *
   * @var \App\Service\Menu\MenuInterface
   */
  protected $hidden;

  /**
   * Class constructor.
   */
  public function __construct(
    MenuInterface $main_menu,
    MenuInterface $footer_menu,
    MenuInterface $hidden_menu
  ) {
    $this->main = $main_menu;
    $this->footer = $footer_menu;
    $this->hidden = $hidden_menu;
  }

  /**
   * Attempt to get a menu by name.
   */
  public function get(string $menu_name): ?MenuInterface {
    return $this->{$menu_name} ?? NULL;
  }

}
