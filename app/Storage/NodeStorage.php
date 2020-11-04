<?php

declare(strict_types=1);

namespace App\Storage;

use App\Service\Storage\NodeStorage as BaseStorage;
use Drupal\Core\Entity\EntityInterface;

/**
 * Extends base node storage to assign entities base on node type.
 */
class NodeStorage extends BaseStorage implements NodeStorageInterface {

  /**
   * Get page by title.
   */
  public function getPageByTitle(string $title): ?EntityInterface {
    $query = $this->getBundleQuery('page')
      ->condition('title', $title)
      ->range(0, 1);

    if (!$pages = $query->execute()) {
      return NULL;
    }

    return $this->load(array_pop($pages));
  }

}
