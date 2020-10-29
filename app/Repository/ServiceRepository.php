<?php

declare(strict_types=1);

namespace App\Repository;

use App\Service\Repository\NodeRepository;

/**
 * Service repository class.
 */
class ServiceRepository extends NodeRepository implements ServiceRepositoryInterface {

  /**
   * {@inheritdoc}
   */
  protected string $bundle = 'service';

}
