<?php

declare(strict_types=1);

namespace App\Repository;

use App\Service\Repository\NodeRepository;

/**
 * Article repository class.
 */
class ArticleRepository extends NodeRepository implements ArticleRepositoryInterface {

  /**
   * {@inheritdoc}
   */
  protected string $bundle = 'article';

}
