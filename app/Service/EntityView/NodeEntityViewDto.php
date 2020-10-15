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
   * Heading.
   *
   * @var string
   */
  protected $heading;

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
   * Layouts.
   *
   * @var array
   */
  protected $layouts;

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
   * Get the heading.
   */
  public function heading(): string {
    return $this->heading;
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
   * Get the layouts.
   */
  public function layouts(): ?array {
    return $this->layouts;
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
