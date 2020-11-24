<?php

declare(strict_types=1);

namespace App\Storage;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines an interface for the team entity storage class.
 */
interface TeamMemberStorageInterface extends ContentEntityStorageInterface {

  /**
   * Get the team members or each department in turn.
   */
  public function getAllByDept(): ?array;

  /**
   * Get the team members by department taxonomy id.
   */
  public function getByDeptId(int $dept_id): ?array;

  /**
   * Get the team members by department taxonomy name.
   */
  public function getByDeptName(string $dept_name): ?array;

}
