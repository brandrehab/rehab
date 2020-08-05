<?php

declare(strict_types=1);

namespace App\EntityView;

use App\Service\EntityView\NodeEntityViewDto;

/**
 * Home data transfer object.
 */
class HomeEntityViewDto extends NodeEntityViewDto {

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

}
