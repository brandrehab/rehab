<?php

declare(strict_types=1);

namespace App\Service\Entity;

/**
 * Controls menu link status trait.
 */
trait ControlsMenuLinkStatusTrait {

  /**
   * Update child menu links.
   */
  public function controlMenuItemStatus(): void {
    if (!$menu_link = $this->get('menu_link')->entity) {
      return;
    }
    $menu_link->set('enabled', $this->menu['link_disabled'] ? FALSE : TRUE);
    $menu_link->save();
  }

}
