<?php

declare(strict_types=1);

namespace App\Storage;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines an interface for  the timeline event storage class.
 */
interface TimelineEventStorageInterface extends ContentEntityStorageInterface {

  /**
   * Get the timeline events ordered by date.
   */
  public function getAll(?string $order = 'ASC'): ?array;

}
