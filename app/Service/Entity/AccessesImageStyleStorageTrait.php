<?php

declare(strict_types=1);

namespace App\Service\Entity;

use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Accesses the image style storage class trait.
 */
trait AccessesImageStyleStorageTrait {

  /**
   * The image style storage class.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface|null
   */
  private static ?EntityStorageInterface $imageStyleStorage = NULL;

  /**
   * Get the image style storage class.
   */
  protected function getImageStyleStorage(): EntityStorageInterface {
    if (self::$imageStyleStorage) {
      return self::$imageStyleStorage;
    }
    self::$imageStyleStorage = $this->entityTypeManager()->getStorage('image_style');
    return self::$imageStyleStorage;
  }

}
