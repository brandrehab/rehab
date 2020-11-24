<?php

declare(strict_types=1);

namespace App\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a timeline event entity type.
 */
interface TimelineEventInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the timeline event creation timestamp.
   */
  public function getCreatedTime(): string;

  /**
   * Gets the heading of the timeline event.
   */
  public function getHeading(): string;

  /**
   * Gets the timestamp of the timeline event.
   */
  public function getTimestamp(): int;

  /**
   * Gets the date of the timeline event.
   */
  public function getDate(?string $date_format = 'F Y'): string;

  /**
   * Gets the details of the timeline event.
   */
  public function getDetails(): string;

  /**
   * Sets the team member creation timestamp.
   */
  public function setCreatedTime(string $timestamp): TimelineEventInterface;

}
