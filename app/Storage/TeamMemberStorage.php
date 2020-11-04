<?php

declare(strict_types=1);

namespace App\Storage;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for team members.
 *
 * This extends the base storage class, adding required special handling for
 * team entities.
 */
class TeamMemberStorage extends SqlContentEntityStorage implements TeamMemberStorageInterface {

  /**
   * Get the directors by department taxonomy id.
   */
  public function getByDeptId(int $dept_id): ?array {
    $query = $this->getQuery()
      ->condition('field_department', $dept_id)
      ->sort($this->entityType->getKey('department'))
      ->sort($this->entityType->getKey('lastname'))
      ->sort($this->entityType->getKey('firstname'));

    if (!$director_ids = $query->execute()) {
      return NULL;
    }

    return $this->loadMultiple($director_ids);
  }

  /**
   * Get the directors by department taxonomy name.
   */
  public function getByDeptName(string $dept_name): ?array {
    $query = $this->entityTypeManager->getStorage('taxonomy_term')->getQuery()
      ->condition('vid', 'team_departments')
      ->condition('name', $dept_name)
      ->range(0, 1);

    if (!$dept_ids = $query->execute()) {
      return NULL;
    }

    return $this->getByDeptId((int) array_pop($dept_ids));
  }

}
