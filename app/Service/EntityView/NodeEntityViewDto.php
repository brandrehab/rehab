<?php

declare(strict_types=1);

namespace App\Service\EntityView;

/**
 * Node data transfer object.
 */
class NodeEntityViewDto extends EntityViewDto {

  /**
   * Id.
   *
   * @var int
   */
  protected $id;

  /**
   * Preview.
   *
   * @var bool
   */
  protected $preview;

  /**
   * Title.
   *
   * @var string
   */
  protected $title;

  /**
   * Url.
   *
   * @var string
   */
  protected $url;

  /**
   * Short url.
   *
   * @var string
   */
  protected $shorturl;

  /**
   * Created.
   *
   * @var int
   */
  protected $created;

  /**
   * Updated.
   *
   * @var int
   */
  protected $updated;

  /**
   * Seo.
   *
   * @var array
   */
  protected $seo;

  /**
   * Social.
   *
   * @var array|null
   */
  protected $social;

  /**
   * Get the id.
   */
  public function id(): int {
    return $this->id;
  }

  /**
   * Get preview state.
   */
  public function preview(): bool {
    return $this->preview;
  }

  /**
   * Get the title.
   */
  public function title(): string {
    return $this->title;
  }

  /**
   * Get the url.
   */
  public function url(): string {
    return $this->url;
  }

  /**
   * Get the shorturl.
   */
  public function shorturl(): string {
    return $this->url;
  }

  /**
   * Get the created timestamp.
   */
  public function created(): int {
    return $this->created;
  }

  /**
   * Get the updated timestamp.
   */
  public function updated(): int {
    return $this->updated;
  }

  /**
   * Get the seo.
   */
  public function seo(): array {
    return $this->seo;
  }

  /**
   * Get the social media images.
   */
  public function social(): ?array {
    return $this->seo;
  }

}