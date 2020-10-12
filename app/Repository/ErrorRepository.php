<?php

declare(strict_types=1);

namespace App\Repository;

use App\Service\Repository\NodeRepository;

/**
 * Error repository class.
 */
class ErrorRepository extends NodeRepository implements ErrorRepositoryInterface {

  /**
   * {@inheritdoc}
   */
  protected $bundle = 'error';

}
