<?php

declare(strict_types=1);

namespace App\Service\Entity;

use Drupal\node\NodeInterface as BaseNodeInterface;

/**
 * Node entity interface.
 */
interface NodeInterface extends BaseNodeInterface {

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
   * Get the entity created timestamp.
   */
  public function getUpdated(): int;

  /**
   * Get the entity seo data.
   */
  public function getSeo(): array;

  /**
   * Get the optional social media image.
   */
  public function getSocial(): ?array;

}
