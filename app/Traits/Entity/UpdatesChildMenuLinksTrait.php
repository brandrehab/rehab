<?php

declare(strict_types=1);

namespace App\Traits\Entity;

use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Updates child menu links trait.
 */
trait UpdatesChildMenuLinksTrait {

  use AccessesNavigationStorageTrait;

  /**
   * Update child menu links.
   */
  public function updateChildMenuLinks(EntityStorageInterface $storage): void {
    if ($this->isNew()) {
      return;
    }

    if (!$menu_link = $this->get('menu_link')->entity) {
      return;
    }

    $menu_id = $menu_link->menu_name->first()->value;
    $menu_link_id = $menu_link->getPluginId();

    $navigation = $this->getNavigationStorage()->getByMenuId($menu_id);
    $nids = $navigation->nids(1, 1, $menu_link_id);

    if (empty($nids)) {
      return;
    }

    $child_nodes = $storage->loadMultiple($nids);
    foreach ($child_nodes as $child_node) {
      $child_node->save();
    }
  }

}
