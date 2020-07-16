<?php

declare(strict_types=1);

namespace App\EntityView;

use App\Service\EntityView\EntityViewDto;

/**
 * Error data transfer object.
 */
class ErrorEntityViewDto extends EntityViewDto {

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
