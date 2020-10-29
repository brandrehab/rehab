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
  public string $entityViewDto = ArticleEntityViewDto::class;

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
