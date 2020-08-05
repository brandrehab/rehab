<?php

declare(strict_types=1);

namespace App\Service\EntityView;

/**
 * Node data transfer object.
 */
class NodeEntityViewDto extends EntityViewDto {

  /**
   * Title.
   *
   * @var string
   */
  protected $title;

  /**
   * Get the title.
   */
  public function title(): string {
    return $this->title;
  }

}
