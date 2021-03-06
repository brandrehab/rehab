<?php

declare(strict_types=1);

namespace App\Service\Entity;

use App\Traits\Entity\AccessesImageStyleStorageTrait;

use Drupal\node\Entity\Node as NodeBase;

/**
 * Base entity for nodes of all types.
 */
class Node extends NodeBase implements NodeInterface {

  use AccessesImageStyleStorageTrait;

  /**
   * Get the optional entity layouts.
   */
  public function getLayouts(): ?array {
    return NULL;
  }

  /**
   * Is the entity is being previewed.
   */
  public function isPreview(): bool {
    return $this->is_preview == TRUE ? TRUE : FALSE;
  }

  /**
   * Get the entity title.
   */
  public function getTitle(): string {
    return $this->get('title')->value;
  }

  /**
   * Get the entity heading.
   */
  public function getHeading(): string {
    return $this->get('field_heading')->value;
  }

  /**
   * Get the entity url.
   */
  public function getUrl(): string {
    if ($this->getType() == 'home') {
      return '/';
    }
    return $this->toUrl()->toString();
  }

  /**
   * Get the entity short url.
   */
  public function getShortUrl(): string {
    return '/' . $this->toUrl()->getInternalPath();
  }

  /**
   * Get the entity created timestamp.
   */
  public function getCreated(): int {
    return (int) $this->get('created')->value;
  }

  /**
   * Gets the entity created date.
   */
  public function getCreatedDate(?string $date_format = 'F Y'): string {
    return date($date_format, $this->getCreated());
  }

  /**
   * Get the entity updated timestamp.
   */
  public function getUpdated(): int {
    return (int) $this->get('changed')->value;
  }

  /**
   * Gets the entity updated date.
   */
  public function getUpdatedDate(?string $date_format = 'F Y'): string {
    return date($date_format, $this->getUpdated());
  }

  /**
   * Get the entity seo data.
   */
  public function getSeo(): array {
    $meta = $this->get('field_seo_and_social_media')->entity;
    return [
      'meta_title' => $meta->get('field_meta_title')->value,
      'meta_description' => $meta->get('field_meta_description')->value,
    ];
  }

  /**
   * Get the optional social media image.
   */
  public function getSocial(): ?array {
    $social = $this->get('field_seo_and_social_media')->entity;

    if ($social->get('field_social_media_image')->isEmpty()) {
      return NULL;
    }

    $media = $social->get('field_social_media_image')->entity;
    $file = $media->get('field_media_image')->entity;
    $style = $this->getImageStyleStorage()->load('social_media_image');

    return [
      'src' => $style->buildUrl($file->getFileUri()),
    ];
  }

}
