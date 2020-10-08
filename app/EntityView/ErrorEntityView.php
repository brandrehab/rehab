<?php

declare(strict_types=1);

namespace App\EntityView;

use App\Service\EntityView\NodeEntityView;

/**
 * Error entity view.
 */
class ErrorEntityView extends NodeEntityView {

  /**
   * Dto class.
   *
   * @var string
   */
  public $entityViewDto = ErrorEntityViewDto::class;

  /**
   * View definitions.
   *
   * @var array
   */
  protected $definitions = [
    'full' => [
      'id',
      'preview',
      'title',
      'heading',
      'url',
      'shorturl',
      'created',
      'updated',
      'seo',
      'social',
    ],
  ];

}
