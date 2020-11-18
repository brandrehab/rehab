<?php

declare(strict_types=1);

namespace App\Traits\Entity;

use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Accesses it's own storage class trait.
 */
trait AccessesEntityStorageTrait {

  /**
   * The entity storage class.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface|null
   */
  private static ?EntityStorageInterface $entityStorage = NULL;

  /**
   * Gets the entity storage.
   */
  protected function getStorage(): EntityStorageInterface {
    if (self::$entityStorage) {
      return self::$entityStorage;
    }
    self::$entityStorage = $this->entityTypeManager()->getStorage($this->getEntityTypeId());
    return self::$entityStorage;
  }

}
