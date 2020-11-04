<?php

declare(strict_types=1);

namespace App\Storage;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines an interface for team entity storage classes.
 */
interface TeamMemberStorageInterface extends ContentEntityStorageInterface {

  /**
   * Get the directors by department taxonomy id.
   */
  public function getByDeptId(int $dept_id): ?array;

  /**
   * Get the directors by department taxonomy name.
   */
  public function getByDeptName(string $dept_name): ?array;

}
