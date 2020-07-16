<?php

declare(strict_types=1);

namespace App\Service\Repository;

use Drupal\Core\Entity\EntityInterface;

/**
 * Entity repository interface.
 */
interface EntityRepositoryInterface {

  /**
   * Find with matching id.
   */
  public function find(int $id) : ?EntityInterface;

  /**
   * Find all with matching ids.
   */
  public function findAll(array $ids) : ?array;

}
