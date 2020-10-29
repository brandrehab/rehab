<?php

declare(strict_types=1);

namespace App\EntityView;

use App\Service\EntityView\NodeEntityView;

/**
 * Service entity view.
 */
class ServiceEntityView extends NodeEntityView {

  /**
   * Dto class.
   *
   * @var string
   */
  public string $entityViewDto = ServiceEntityViewDto::class;

  /**
   * View definitions.
   *
   * @var array
   */
  protected array $definitions = [
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
