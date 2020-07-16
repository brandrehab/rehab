<?php

declare(strict_types=1);

namespace App\EntityView;

use App\Service\EntityView\EntityViewDto;

/**
 * Page data transfer object.
 */
class PageEntityViewDto extends EntityViewDto {

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
