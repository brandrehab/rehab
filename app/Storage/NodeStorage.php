<?php

declare(strict_types=1);

namespace App\Storage;

use App\Entity\PageInterface;
use App\Service\Storage\NodeStorage as BaseStorage;

/**
 * Extends base node storage to assign entities base on node type.
 */
class NodeStorage extends BaseStorage implements NodeStorageInterface {

  /**
   * Get page by title.
   */
  public function getPageByTitle(string $title): ?PageInterface {
    $query = $this->getBundleQuery('page')
      ->condition('title', $title)
      ->range(0, 1);

    if (!$pages = $query->execute()) {
      return NULL;
    }

    return $this->load(array_pop($pages));
  }

}
