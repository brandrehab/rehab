<?php

namespace App\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a team-member entity type.
 */
interface TeamMemberInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the team member creation timestamp.
   */
  public function getCreatedTime(): string;

  /**
   * Gets the calculated team member name.
   */
  public function getFullname(): string;

  /**
   * Sets the team member creation timestamp.
   */
  public function setCreatedTime(string $timestamp): TeamMemberInterface;

}
