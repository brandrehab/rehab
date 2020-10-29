<?php

declare(strict_types=1);

namespace App\Service\Block;

/**
 * The head service.
 */
class HeadService implements HeadServiceInterface {

  /**
   * Meta data relating to the current page.
   *
   * @var array
   */
  static protected array $meta;

  /**
   * JsonLd relating to the current page.
   *
   * @var string|null
   */
  static protected ?string $jsonld;

  /**
   * Social media link support relating to the current page.
   *
   * @var array|null
   */
  static protected ?array $social;

  /**
   * Canonical link to the current page.
   *
   * @var string
   */
  static protected string $canonical;

  /**
   * Short url for the current page.
   *
   * @var string
   */
  static protected string $shortUrl;

  /**
   * Get meta data.
   */
  public function meta(): array {
    return self::$meta;
  }

  /**
   * Get jsonld.
   */
  public function jsonld(): ?string {
    return self::$jsonld;
  }

  /**
   * Get social media link support.
   */
  public function social(): ?array {
    return self::$social;
  }

  /**
   * Get the canonical link.
   */
  public function canonical(): string {
    return self::$canonical;
  }

  /**
   * Get the shortUrl link.
   */
  public function shortUrl(): string {
    return self::$shortUrl;
  }

  /**
   * Set meta title.
   */
  public function setMetaTitle(string $meta_title): void {
    self::$meta['title'] = $meta_title;
  }

  /**
   * Set meta description.
   */
  public function setMetaDescription(string $meta_description): void {
    self::$meta['description'] = $meta_description;
  }

}
