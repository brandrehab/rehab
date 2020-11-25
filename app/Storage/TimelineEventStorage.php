<?php

declare(strict_types=1);

namespace App\Storage;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for timeline events.
 *
 * This extends the base storage class, adding required special handling for
 * timeline entities.
 */
class TimelineEventStorage extends SqlContentEntityStorage implements TimelineEventStorageInterface {

  /**
   * Get the timeline events ordered by date.
   */
  public function getAll(?string $order = 'ASC'): ?array {
    $query = $this->getQuery()
      ->sort($this->entityType->getKey('date'), $order);

    if (!$timeline_ids = $query->execute()) {
      return NULL;
    }

    return $this->loadMultiple($timeline_ids);
  }

}
