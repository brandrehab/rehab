<?php

declare(strict_types=1);

namespace App\Repository;

use App\Service\Repository\NodeRepository;

/**
 * Overview repository class.
 */
class OverviewRepository extends NodeRepository implements OverviewRepositoryInterface {

  /**
   * {@inheritdoc}
   */
  protected string $bundle = 'overview';

}
