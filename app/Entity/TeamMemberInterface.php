<?php

namespace App\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a team member entity type.
 */
interface TeamMemberInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the team member creation timestamp.
   */
  public function getCreatedTime(): string;

  /**
   * Gets the firstname of the team member.
   */
  public function getFirstname(): string;

  /**
   * Gets the lastname of the team member.
   */
  public function getLastname(): string;

  /**
   * Gets the calculated team member name.
   */
  public function getFullname(): string;

  /**
   * Gets the optional email address of the team member.
   */
  public function getEmail(): ?string;

  /**
   * Gets the optional telephone number of the team member.
   */
  public function getTelephone(): ?string;

  /**
   * Gets the position held by the team member.
   */
  public function getPosition(): string;

  /**
   * Gets the department to which the team member belongs.
   */
  public function getDepartment(): string;

  /**
   * Sets the team member creation timestamp.
   */
  public function setCreatedTime(string $timestamp): TeamMemberInterface;

}
