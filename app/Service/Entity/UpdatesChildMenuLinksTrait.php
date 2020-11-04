<?php

declare(strict_types=1);

namespace App\Service\Entity;

use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Updates child menu links trait.
 */
trait UpdatesChildMenuLinksTrait {

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

    $menu_name = $menu_link->menu_name->first()->value;
    $menu_link_id = $menu_link->getPluginId();

    $menu = $storage->getMenuRepository()->get($menu_name);

    $links = $menu->build(1, 1, $menu_link_id);
    $nids = $menu->getNids($links);

    if (empty($nids)) {
      return;
    }

    $child_nodes = $storage->loadMultiple($nids);
    foreach ($child_nodes as $child_node) {
      $child_node->save();
    }
  }

}
