<?php

declare(strict_types=1);

namespace App\Traits\Entity;

use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Accesses the navigation storage class trait.
 */
trait AccessesNavigationStorageTrait {

  /**
   * The navigation storage class.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface|null
   */
  private static ?EntityStorageInterface $navigationStorage = NULL;

  /**
   * Gets the navigation storage.
   */
  protected function getNavigationStorage(): EntityStorageInterface {
    if (self::$navigationStorage) {
      return self::$navigationStorage;
    }
    self::$navigationStorage = $this->entityTypeManager()->getStorage('navigation');
    return self::$navigationStorage;
  }

}
