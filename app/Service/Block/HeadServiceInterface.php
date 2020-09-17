<?php

declare(strict_types=1);

namespace App\Service\Block;

/**
 * The head service interface.
 */
interface HeadServiceInterface {

  /**
   * Get meta data.
   */
  public function meta(): array;

  /**
   * Get jsonld.
   */
  public function jsonld(): ?string;

  /**
   * Get social media link support.
   */
  public function social(): ?array;

  /**
   * Get the canonical link.
   */
  public function canonical(): string;

  /**
   * Get the shortUrl link.
   */
  public function shortUrl(): string;

  /**
   * Set meta title.
   */
  public function setMetaTitle(string $meta_title): void;

  /**
   * Set meta description.
   */
  public function setMetaDescription(string $meta_description): void;

}
