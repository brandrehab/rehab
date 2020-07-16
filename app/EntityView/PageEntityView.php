<?php

declare(strict_types=1);

namespace App\EntityView;

use App\Service\EntityView\NodeEntityView;

/**
 * Page entity view.
 */
class PageEntityView extends NodeEntityView {

  /**
   * Dto class.
   *
   * @var string
   */
  public $entityViewDto = PageEntityViewDto::class;

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
      'url',
      'created',
      'updated',
    ],
  ];

}
