<?php

declare(strict_types=1);

namespace App\EntityView;

use App\Service\EntityView\NodeEntityViewDto;

/**
 * Service data transfer object.
 */
class ArticleEntityViewDto extends NodeEntityViewDto {

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
