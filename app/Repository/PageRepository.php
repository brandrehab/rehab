<?php

declare(strict_types=1);

namespace App\Repository;

use App\Service\Repository\NodeRepository;

/**
 * Page repository class.
 */
class PageRepository extends NodeRepository implements PageRepositoryInterface {

  /**
   * {@inheritdoc}
   */
  protected string $bundle = 'page';

}
