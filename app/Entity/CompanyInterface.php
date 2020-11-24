<?php

declare(strict_types=1);

namespace App\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a company entity type.
 */
interface CompanyInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the company creation timestamp.
   */
  public function getCreatedTime(): string;

  /**
   * Get the company name.
   */
  public function getName(): string;

  /**
   * Get the optional registered company name.
   */
  public function getRegisteredName(): ?string;

  /**
   * Get the optional company registration number.
   */
  public function getRegistrationNumber(): ?string;

  /**
   * Get the optional country of registration.
   */
  public function getCountryOfRegistration(): ?string;

  /**
   * Checks whether all the registration information exists.
   */
  public function isRegistered(): bool;

  /**
   * Sets the company creation timestamp.
   */
  public function setCreatedTime(string $timestamp): CompanyInterface;

}
