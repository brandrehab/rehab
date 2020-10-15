<?php

declare(strict_types=1);

namespace App\EntityView;

use App\Service\EntityView\NodeEntityView;

/**
 * Overview entity view.
 */
class OverviewEntityView extends NodeEntityView {

  /**
   * Dto class.
   *
   * @var string
   */
  public $entityViewDto = OverviewEntityViewDto::class;

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
      'layouts',
      'created',
      'updated',
      'seo',
      'social',
    ],
  ];

}
