<?php

declare(strict_types=1);

namespace App\EntityView;

use App\Service\EntityView\NodeEntityView;

/**
 * Article entity view.
 */
class ArticleEntityView extends NodeEntityView {

  /**
   * Dto class.
   *
   * @var string
   */
  public $entityViewDto = ArticleEntityViewDto::class;

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
