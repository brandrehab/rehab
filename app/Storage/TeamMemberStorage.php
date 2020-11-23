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
   * Get the team members or each department in turn.
   */
  public function getAllByDept(): ?array {
    $departmentStorage = $this->entityTypeManager->getStorage('taxonomy_term');
    $query = $departmentStorage->getQuery()
      ->condition('vid', 'team_departments')
      ->sort('weight');

    if (!$dept_ids = $query->execute()) {
      return NULL;
    }

    $departments = $departmentStorage->loadMultiple($dept_ids);

    foreach ($departments as $department) {
      $dept_name = $department->get('name')->value;
      $dept_members = $this->getByDeptId((int) $department->id());
      $members[$dept_name] = $dept_members;
    }

    return $members ?? NULL;
  }

  /**
   * Get the team members by department taxonomy id.
   */
  public function getByDeptId(int $dept_id): ?array {
    $query = $this->getQuery()
      ->condition('field_department', $dept_id)
      ->sort($this->entityType->getKey('lastname'))
      ->sort($this->entityType->getKey('firstname'));

    if (!$member_ids = $query->execute()) {
      return NULL;
    }

    return $this->loadMultiple($member_ids);
  }

  /**
   * Get the team members by department taxonomy name.
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
