<?php

declare(strict_types=1);

namespace App\Service\Entity;

use Drupal\node\NodeInterface as BaseNodeInterface;

/**
 * Node entity interface.
 */
interface NodeInterface extends BaseNodeInterface {

  /**
   * Get the optional entity layouts.
   */
  public function getLayouts(): ?array;

  /**
   * Is the entity is being previewed.
   */
  public function isPreview(): bool;

  /**
   * Get the entity title.
   */
  public function getTitle(): string;

  /**
   * Get the entity heading.
   */
  public function getHeading(): string;

  /**
   * Get the entity url.
   */
  public function getUrl(): string;

  /**
   * Get the entity short url.
   */
  public function getShortUrl(): string;

  /**
   * Get the entity created timestamp.
   */
  public function getCreated(): int;

  /**
   * Gets the entity created date.
   */
  public function getCreatedDate(?string $date_format = 'F Y'): string;

  /**
   * Get the entity updatedtimestamp.
   */
  public function getUpdated(): int;

  /**
   * Gets the entity updated date.
   */
  public function getUpdatedDate(?string $date_format = 'F Y'): string;

  /**
   * Get the entity seo data.
   */
  public function getSeo(): array;

  /**
   * Get the optional social media image.
   */
  public function getSocial(): ?array;

}
