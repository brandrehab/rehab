<?php

declare(strict_types=1);

namespace App\Repository;

use App\Service\Repository\NodeRepository;

/**
 * Home repository class.
 */
class HomeRepository extends NodeRepository implements HomeRepositoryInterface {

  /**
   * {@inheritdoc}
   */
  protected $bundle = 'home';

}
