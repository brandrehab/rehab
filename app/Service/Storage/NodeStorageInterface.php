<?php

declare(strict_types=1);

namespace App\Service\Storage;

use Drupal\Core\Entity\Query\QueryInterface;
use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines an interface for the node entity storage class.
 */
interface NodeStorageInterface extends ContentEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function getBundleQuery(string $bundle, string $conjugation = 'AND'): QueryInterface;

}
