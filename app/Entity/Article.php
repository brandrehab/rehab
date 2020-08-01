<?php

declare(strict_types=1);

namespace App\Entity;

use App\EntityView\ArticleEntityView;
use App\Service\Entity\Node;

/**
 * Article entity.
 */
class Article extends Node implements ArticleInterface {

  /**
   * Associate an entity view with this entity.
   *
   * @var string
   */
  protected $entityView = ArticleEntityView::class;

}
