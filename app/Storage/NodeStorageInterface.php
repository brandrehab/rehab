<?php

declare(strict_types=1);

namespace App\Storage;

use App\Service\Storage\NodeStorageInterface as BaseStorageInterface;
use Drupal\Core\Entity\EntityInterface;

/**
 * Defines an interface for the node entity storage class.
 */
interface NodeStorageInterface extends BaseStorageInterface {

  /**
   * Get page by title.
   */
  public function getPageByTitle(string $title): ?EntityInterface;

}
