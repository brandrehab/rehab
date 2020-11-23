<?php

declare(strict_types=1);

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
   * Tests whether the team member has aan email address.
   */
  public function hasEmail(): bool;

  /**
   * Gets the optional telephone number of the team member.
   */
  public function getTelephone(): ?string;

  /**
   * Tests whether the team member has a telephone number.
   */
  public function hasTelephone(): bool;

  /**
   * Gets the optional telephone number of the team member as a link.
   */
  public function getTelephoneLink(): ?array;

  /**
   * Tests whether the team member has a telephone number with an extension.
   */
  public function hasTelephoneExtension(): bool;

  /**
   * Gets the position held by the team member.
   */
  public function getPosition(): string;

  /**
   * Gets the department to which the team member belongs.
   */
  public function getDepartment(): string;

  /**
   * Gets the optional facebook page of the team member.
   */
  public function getFacebook(): ?string;

  /**
   * Tests whether the team member has a facebook page.
   */
  public function hasFacebook(): bool;

  /**
   * Gets the optional twitter page of the team member.
   */
  public function getTwitter(): ?string;

  /**
   * Tests whether the team member has a twitter page.
   */
  public function hasTwitter(): bool;

  /**
   * Gets the optional linkedin page of the team member.
   */
  public function getLinkedIn(): ?string;

  /**
   * Tests whether the team member has a linkedin page.
   */
  public function hasLinkedIn(): bool;

  /**
   * Sets the team member creation timestamp.
   */
  public function setCreatedTime(string $timestamp): TeamMemberInterface;

}
